<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class CertificateGenerationController extends Controller
{
    /**
     * Show the form to generate a Certificate of Indigency.
     */
    public function createIndigency()
    {
        $preselectedResident = null;
        if (old('resident_id')) {
            $preselectedResident = Resident::find(old('resident_id'));
        }

        return view('admin.certificates.indigency', compact('preselectedResident'));
    }

    /**
     * Return residents for Select2 AJAX (search, limit 5).
     */
    public function searchResidents(Request $request)
    {
        $q = $request->input('q', '');
        $limit = min((int) $request->input('limit', 5), 10);

        $query = Resident::query()
            ->orderBy('last_name')
            ->orderBy('first_name');

        if (strlen($q) >= 1) {
            $query->where(function ($qb) use ($q) {
                $qb->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('middle_name', 'like', "%{$q}%");
            });
        }

        $residents = $query->limit($limit)->get();

        $results = $residents->map(fn (Resident $r) => [
            'id' => $r->id,
            'text' => $r->full_name . ($r->rbi_no ? " ({$r->rbi_no})" : ''),
        ])->values()->all();

        return response()->json(['results' => $results]);
    }

    /**
     * Generate and download the Certificate of Indigency as a Word document.
     */
    public function generateIndigency(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => ['required', 'integer', 'exists:residents,id'],
            'purpose' => ['required', 'string', 'max:500'],
        ]);

        $resident = Resident::with('household')->findOrFail($validated['resident_id']);

        $address = $this->getResidentAddress($resident);
        if ($address === null) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Selected resident has no household address. Please assign a household first.');
        }

        $dateIssued = $this->formatDateOrdinal(now());
        $recipientOfficial = 'HON. ART JOSEPH FRANCIS MERCADO, CITY MAYOR OF SAN PEDRO, LAGUNA';
        $punongBarangay = 'HON. ROMEO B. BONOAN';
        $barangayName = 'BARANGAY SAN LORENZO RUIZ';
        $barangayAddress = 'QUEENSLAND ST., GREATLAND VILLAGE, CITY OF SAN PEDRO, LAGUNA';
        $contactNos = 'Tel. Nos. 8646 0519 / 8252 4884';

        $phpWord = new PhpWord;
        $section = $phpWord->addSection();

        // Header
        $section->addText('REPUBLIKA NG PILIPINAS', [], ['alignment' => Jc::CENTER]);
        $section->addText('LALAWIGAN NG LAGUNA', [], ['alignment' => Jc::CENTER]);
        $section->addText('LUNGSOD NG SAN PEDRO', [], ['alignment' => Jc::CENTER]);
        $section->addText($barangayName, ['bold' => true], ['alignment' => Jc::CENTER]);
        $section->addText($barangayAddress, [], ['alignment' => Jc::CENTER]);
        $section->addText($contactNos, [], ['alignment' => Jc::CENTER]);
        $section->addText('OFFICE OF THE PUNONG BARANGAY', [], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(1);

        // Title
        $section->addText('CERTIFICATE OF INDIGENCY', ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(2);

        // Body paragraph 1
        $body1 = "This is to certify that {$resident->full_name} is a bona fide resident of {$address}, from the low-income sector of our community and could not afford the {$validated['purpose']}.";
        $section->addText($body1, [], ['alignment' => Jc::BOTH]);
        $section->addTextBreak(1);

        // Body paragraph 2
        $body2 = "This certification is issued upon his/her request to ask for {$validated['purpose']} from your good office: {$recipientOfficial}";
        $section->addText($body2, [], ['alignment' => Jc::BOTH]);
        $section->addTextBreak(1);

        $body3 = "Given this {$dateIssued} at Barangay San Lorenzo Ruiz, City of San Pedro, Province of Laguna.";
        $section->addText($body3, [], ['alignment' => Jc::BOTH]);
        $section->addTextBreak(3);

        // Signatory (right-aligned)
        $section->addText($punongBarangay, ['bold' => true], ['alignment' => Jc::END]);
        $section->addText('PUNONG BARANGAY', [], ['alignment' => Jc::END]);
        $section->addTextBreak(2);

        // Footer info
        $section->addText('Paid Under O. R. #: EXEMPTED', [], []);
        $section->addText('Amount: EXEMPTED', [], []);
        $section->addText('Date: ' . now()->format('m/d/Y'), [], []);
        $section->addText('VALID ONLY ONE (1) MONTH UPON ISSUANCE', [], []);

        $safeName = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $resident->full_name);
        $filename = "Certificate-of-Indigency-{$safeName}-" . now()->format('Y-m-d') . '.docx';

        $tempPath = storage_path('app/temp/' . uniqid('indigency_', true) . '.docx');
        if (! is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    private function getResidentAddress(Resident $resident): ?string
    {
        if (! $resident->household) {
            return null;
        }
        $h = $resident->household;
        $first = trim($h->first_address ?? $h->blk_lot_unit ?? '');
        $second = trim($h->second_address ?? '');
        if ($first === '' && $second === '') {
            return null;
        }
        if ($second !== '') {
            return $first . ', ' . $second;
        }
        return $first;
    }

    private function formatDateOrdinal(Carbon $date): string
    {
        $day = $date->day;
        $suffix = match (true) {
            $day >= 11 && $day <= 13 => 'th',
            $day % 10 === 1 => 'st',
            $day % 10 === 2 => 'nd',
            $day % 10 === 3 => 'rd',
            default => 'th',
        };
        return $date->format("j{$suffix} day of F, Y");
    }
}
