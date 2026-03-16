<?php

namespace App\Jobs;

use App\Models\Household;
use App\Models\Resident;
use App\Models\ResidentCsvImport;
use App\Models\ResidentCsvImportFailure;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ImportResidentsFromCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $importId)
    {
    }

    public function handle(): void
    {
        $import = ResidentCsvImport::find($this->importId);

        if (!$import) {
            return;
        }

        $import->update([
            'status' => 'processing',
            'started_at' => now(),
            'error_message' => null,
        ]);

        try {
            if (!Storage::disk('local')->exists($import->file_path)) {
                throw new Exception('Uploaded CSV file was not found.');
            }

            $fullPath = Storage::disk('local')->path($import->file_path);

            if (!is_file($fullPath)) {
                throw new Exception('Uploaded CSV file was not found.');
            }

            $handle = fopen($fullPath, 'r');
            if ($handle === false) {
                throw new Exception('Unable to open uploaded CSV file.');
            }

            $header = fgetcsv($handle);
            if ($header === false) {
                fclose($handle);
                throw new Exception('CSV file has no header row.');
            }

            $headers = $this->normalizeHeaders($header);

            $processed = 0;
            $success = 0;
            $failed = 0;
            $rowNumber = 1;

            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                if ($this->isEmptyRow($row)) {
                    continue;
                }

                $processed++;
                $payload = $this->mapRow($headers, $row);

                try {
                    $this->importResidentRow($payload);
                    $success++;
                } catch (Throwable $e) {
                    $failed++;

                    ResidentCsvImportFailure::create([
                        'resident_csv_import_id' => $import->id,
                        'row_number' => $rowNumber,
                        'payload' => $payload,
                        'error_message' => $e->getMessage(),
                    ]);
                }

                if ($processed % 10 === 0) {
                    $import->update([
                        'processed_rows' => $processed,
                        'success_rows' => $success,
                        'failed_rows' => $failed,
                    ]);
                }
            }

            fclose($handle);

            $import->update([
                'status' => 'completed',
                'processed_rows' => $processed,
                'success_rows' => $success,
                'failed_rows' => $failed,
                'finished_at' => now(),
            ]);
        } catch (Throwable $e) {
            $import->update([
                'status' => 'failed',
                'finished_at' => now(),
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    protected function importResidentRow(array $payload): void
    {
        $validator = Validator::make($payload, $this->rules());

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        $validated = $validator->validated();

        DB::transaction(function () use ($validated) {
            $addressData = Arr::only($validated, ['subdivision', 'street', 'block', 'lot', 'unit']);
            $residentIdentity = Arr::only($validated, ['first_name', 'middle_name', 'last_name', 'extension_name']);

            $existingResident = Resident::query()
                ->whereHas('household', function ($query) use ($addressData) {
                    $query->where('block', $addressData['block'])
                        ->where('lot', $addressData['lot'])
                        ->whereRaw('LOWER(TRIM(street)) = ?', [mb_strtolower(trim((string) $addressData['street']))])
                        ->whereRaw('LOWER(TRIM(subdivision)) = ?', [mb_strtolower(trim((string) $addressData['subdivision']))]);

                    if (($addressData['unit'] ?? null) === null || trim((string) $addressData['unit']) === '') {
                        $query->whereNull('unit');
                    } else {
                        $query->whereRaw('LOWER(TRIM(unit)) = ?', [mb_strtolower(trim((string) $addressData['unit']))]);
                    }
                })
                ->whereRaw('LOWER(TRIM(first_name)) = ?', [mb_strtolower(trim((string) $residentIdentity['first_name']))])
                ->whereRaw('LOWER(TRIM(last_name)) = ?', [mb_strtolower(trim((string) $residentIdentity['last_name']))])
                ->whereRaw('LOWER(TRIM(COALESCE(middle_name, ""))) = ?', [mb_strtolower(trim((string) ($residentIdentity['middle_name'] ?? '')))])
                ->whereRaw('LOWER(TRIM(COALESCE(extension_name, ""))) = ?', [mb_strtolower(trim((string) ($residentIdentity['extension_name'] ?? '')))])
                ->first();

            if ($existingResident) {
                throw new Exception('Resident already exists in the same household address.');
            }

            $household = Household::firstOrCreate($addressData);

            $residentData = Arr::except($validated, ['subdivision', 'street', 'block', 'lot', 'unit']);
            $residentData['household_id'] = $household->id;
            $residentData['contact_number'] = $residentData['contact_number'] ?? '';
            $residentData['email'] = $residentData['email'] ?? '';

            Resident::create($residentData);
        });
    }

    protected function mapRow(array $headers, array $row): array
    {
        $raw = [];

        foreach ($headers as $index => $header) {
            if ($header === null || $header === '') {
                continue;
            }

            $value = $row[$index] ?? null;
            $raw[$header] = is_string($value) ? trim($value) : $value;
        }

        return $this->transformToResidentPayload($raw);
    }

    protected function normalizeHeaders(array $header): array
    {
        return array_map(function ($value) {
            $value = strtolower(trim((string) $value));
            $value = preg_replace('/[^a-z0-9]+/i', '_', $value ?? '');
            $value = preg_replace('/_+/', '_', $value ?? '');
            return trim((string) $value, '_');
        }, $header);
    }

    protected function transformToResidentPayload(array $raw): array
    {
        $birthday = $this->firstNonEmpty($raw, ['your_date_of_birth', 'birthday']);

        return [
            'first_name' => $this->firstNonEmpty($raw, ['your_first_name', 'first_name']),
            'middle_name' => $this->nullIfEmpty($this->firstNonEmpty($raw, ['your_middle_name_optional', 'middle_name'])),
            'last_name' => $this->firstNonEmpty($raw, ['your_last_name', 'last_name']),
            'extension_name' => $this->nullIfEmpty($this->firstNonEmpty($raw, ['your_extension_name_optional', 'extension_name'])),
            'sex' => $this->firstNonEmpty($raw, ['your_sex', 'sex']),
            'birthday' => $this->normalizeBirthday($birthday),
            'birthplace' => $this->firstNonEmpty($raw, ['your_place_of_birth', 'birthplace']),
            'religion' => $this->firstNonEmpty($raw, ['your_religion', 'religion']),
            'citizenship' => $this->firstNonEmpty($raw, ['your_citizenship', 'citizenship']),
            'civil_status' => $this->firstNonEmpty($raw, ['your_civil_status', 'civil_status']),
            'contact_number' => $this->nullIfEmpty($this->firstNonEmpty($raw, ['contact_number'])),
            'email' => $this->nullIfEmpty($this->firstNonEmpty($raw, ['email_address', 'email'])),
            'ownership' => $this->firstNonEmpty($raw, ['house_of_ownership', 'ownership']),
            'registered_voter' => $this->firstNonEmpty($raw, ['registered_voter', 'registered_voter_']),
            'precinct_number' => $this->nullIfEmpty($this->firstNonEmpty($raw, ['precinct_number'])),
            'subdivision' => $this->firstNonEmpty($raw, ['address_subd_village', 'subdivision']),
            'street' => $this->firstNonEmpty($raw, ['address_street', 'street']),
            'block' => $this->firstNonEmpty($raw, ['address_block', 'block']),
            'lot' => $this->firstNonEmpty($raw, ['address_lot', 'lot']),
            'unit' => $this->nullIfEmpty($this->firstNonEmpty($raw, ['address_apartment_unit', 'unit'])),
            'residence_since' => $this->firstNonEmpty($raw, ['resident_since', 'residence_since']),
            'role' => $this->firstNonEmpty($raw, ['your_relationship_to_the_head_of_the_family', 'role']),
            'educational_attainment' => $this->firstNonEmpty($raw, ['educational_attainment']),
            'occupation' => $this->nullIfEmpty($this->firstNonEmpty($raw, ['your_occupation', 'occupation'])),
            'employment_status' => $this->firstNonEmpty($raw, ['your_employment_status', 'employment_status']),
            'monthly_income' => $this->firstNonEmpty($raw, ['monthly_total_income', 'monthly_income']),
        ];
    }

    protected function firstNonEmpty(array $raw, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $raw)) {
                continue;
            }

            $value = trim((string) ($raw[$key] ?? ''));
            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    protected function nullIfEmpty(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);
        return $trimmed === '' ? null : $trimmed;
    }

    protected function normalizeBirthday(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (Throwable) {
            return $value;
        }
    }

    protected function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    protected function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'extension_name' => 'nullable|string|max:50',
            'sex' => 'required|in:Male,Female',
            'birthday' => 'required|date',
            'civil_status' => 'required|string|max:50',
            'citizenship' => 'required|string|max:255',
            'birthplace' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'registered_voter' => 'required|string|max:50',
            'precinct_number' => 'nullable|string|max:50',
            'subdivision' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'block' => 'required|numeric',
            'lot' => 'required|numeric',
            'unit' => 'nullable|string|max:10',
            'role' => 'required|string|max:255',
            'residence_since' => 'required|integer',
            'ownership' => 'required|string|max:255',
            'educational_attainment' => 'required|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'employment_status' => 'required|string|max:255',
            'monthly_income' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
        ];
    }
}
