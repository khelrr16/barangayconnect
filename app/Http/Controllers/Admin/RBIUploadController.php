<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ImportResidentsFromCsv;
use App\Models\ResidentCsvImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RBIUploadController extends Controller
{
    public function index(){
        $imports = ResidentCsvImport::with('createdByUser')->get();

        return view('admin.rbi.upload', compact('imports'));
    }

    public function failed($csv){
        $import = ResidentCsvImport::with('failures')->findOrFail($csv);

        return view('admin.rbi.upload_failed', compact('import'));
    }

    public function uploadCsv(Request $request)
    {
        $validated = $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv', 'max:20480'],
        ]);

        $storedPath = $validated['csv_file']->store('imports/residents', 'local');
        $fullPath = Storage::disk('local')->path($storedPath);
        $totalRows = $this->countCsvRows($fullPath);

        $import = ResidentCsvImport::create([
            'file_path' => $storedPath,
            'status' => 'queued',
            'total_rows' => $totalRows,
            'created_by' => Auth::id(),
        ]);

        ImportResidentsFromCsv::dispatch($import->id);

        return response()->json([
            'import_id' => $import->id,
            'status' => $import->status,
            'total_rows' => $import->total_rows,
        ]);
    }

    public function importStatus($importId)
    {
        $import = ResidentCsvImport::withCount('failures')->findOrFail($importId);

        $totalRows = max(1, (int) $import->total_rows);
        $percentage = round(((int) $import->processed_rows / $totalRows) * 100, 2);

        if (in_array($import->status, ['completed', 'failed'], true)) {
            $percentage = 100;
        }

        return response()->json([
            'id' => $import->id,
            'status' => $import->status,
            'total_rows' => (int) $import->total_rows,
            'processed_rows' => (int) $import->processed_rows,
            'success_rows' => (int) $import->success_rows,
            'failed_rows' => (int) $import->failed_rows,
            'failure_records' => (int) $import->failures_count,
            'progress' => $percentage,
            'error_message' => $import->error_message,
        ]);
    }

    private function countCsvRows(string $fullPath): int
    {
        if (!is_file($fullPath)) {
            return 0;
        }

        $handle = fopen($fullPath, 'r');
        if ($handle === false) {
            return 0;
        }

        fgetcsv($handle);

        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $isEmpty = true;
            foreach ($row as $value) {
                if (trim((string) $value) !== '') {
                    $isEmpty = false;
                    break;
                }
            }

            if (!$isEmpty) {
                $count++;
            }
        }

        fclose($handle);

        return $count;
    }
}
