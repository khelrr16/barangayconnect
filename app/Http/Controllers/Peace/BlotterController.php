<?php

namespace App\Http\Controllers\Peace;

use App\Http\Controllers\Controller;
use App\Models\BlotterDocument;
use App\Models\BlotterRecord;
use App\Models\Official;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;


class BlotterController extends Controller
{
    public function index()
    {
        $records = BlotterRecord::with(['assignedOfficial', 'documents'])
            ->withCount('documents')
            ->orderByDesc('filed_at')
            ->orderByDesc('id')
            ->get();

        return view('committees.modules.peace.blotter.index', compact('records'));
    }

    public function create()
    {
        $officials = Official::all();

        return view('committees.modules.peace.blotter.create', compact('officials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'complainant_name' => ['required', 'string', 'max:255'],
            'complainant_contact' => ['nullable', 'string', 'max:255'],
            'complainant_address' => ['required', 'string', 'max:2000'],
            'respondent_name' => ['required', 'string', 'max:255'],
            'respondent_contact' => ['nullable', 'string', 'max:255'],
            'respondent_address' => ['required', 'string', 'max:2000'],
            'case_type' => ['required', 'string', 'max:255'],
            'date_filled' => ['required', 'date'],
            'case_description' => ['required', 'string', 'max:5000'],
            'witnesses' => ['nullable', 'string', 'max:2000'],
            'assigned_official_id' => ['nullable', 'integer', 'exists:officials,id'],
            'hearing_datetime' => ['nullable', 'date'],
            'hearing_venue' => ['nullable', 'string', 'max:255'],
            'evidence' => ['nullable', 'array'],
            'evidence.*' => ['file', 'max:10240'], // 10MB per file
        ]);

        $user = Auth::user();

        $record = DB::transaction(function () use ($validated, $request, $user) {
            $blotterNumber = $this->generateBlotterNumber($validated['date_filled']);

            $record = BlotterRecord::create([
                'blotter_number' => $blotterNumber,
                'complainant_name' => $validated['complainant_name'],
                'complainant_contact' => $validated['complainant_contact'] ?? null,
                'complainant_address' => $validated['complainant_address'],
                'respondent_name' => $validated['respondent_name'],
                'respondent_contact' => $validated['respondent_contact'] ?? null,
                'respondent_address' => $validated['respondent_address'],
                'case_type' => $validated['case_type'],
                'date_filled' => $validated['date_filled'],
                'case_description' => $validated['case_description'],
                'witnesses' => $validated['witnesses'] ?? null,
                'assigned_official_id' => $validated['assigned_official_id'] ?? null,
                'hearing_datetime' => $validated['hearing_datetime'] ?? null,
                'hearing_venue' => $validated['hearing_venue'] ?? null,
                'status' => 'Filed',
                'created_by' => $user->id,
            ]);

            $files = $request->file('evidence', []);
            foreach ($files as $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store('blotter-documents/' . $record->blotter_number, 'public');

                BlotterDocument::create([
                    'blotter_id' => $record->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => (string) ($file->getClientOriginalExtension() ?: $file->getClientMimeType()),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => $user->name ?? (string) $user->id,
                ]);
            }

            return $record;
        });

        return redirect()
            ->route('committee.peace.blotter.index')
            ->with('success', 'Blotter case filed successfully (' . $record->blotter_number . ').');
    }

    private function generateBlotterNumber(string $dateFilled): string
    {
        $year = (string) date('Y', strtotime($dateFilled));
        $prefix = 'BLT-' . $year . '-';

        $latest = BlotterRecord::where('blotter_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('blotter_number');

        $next = 1;
        if (is_string($latest) && str_starts_with($latest, $prefix)) {
            $suffix = substr($latest, strlen($prefix));
            if (ctype_digit($suffix)) {
                $next = ((int) $suffix) + 1;
            }
        }

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function show(string $id)
    {
        $blotter = BlotterRecord::with(['assignedOfficial', 'documents'])
            ->withCount('documents')
            ->findOrFail($id);

        $documentsByType = $blotter->documents->groupBy(function (BlotterDocument $document) {
            $type = trim(strtolower((string) $document->file_type));
            return $type !== '' ? $type : 'unknown';
        });

        return view('committees.modules.peace.blotter.show', compact('blotter', 'documentsByType'));
    }

    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'max:255'],
        ]);

        $record = BlotterRecord::findOrFail($id);
        $record->status = $validated['status'];
        $record->save();

        return redirect()
            ->route('committee.peace.blotter.show', $record->id)
            ->with('success', 'Blotter case status updated successfully.');
    }

    public function generateHearing(Request $request, BlotterRecord $blotter)
    {
        $blotter->load('assignedOfficial');

        $receivingAddress = $blotter->respondent_address ?? null;
        if ($receivingAddress === null) {
            return redirect()
                ->back()
                ->with('error', 'Unable to generate certificate. Respondent address is not available.');
        }
        $formattedHearingDate = $blotter->hearing_datetime->format('jS') . ' day of ' . $blotter->hearing_datetime->format('F, Y');

        $punongBarangay = 'HON. ROMEO B. BONOAN';
        $barangayName = 'BARANGAY SAN LORENZO RUIZ';
        $barangayAddress = 'QUEENSLAND ST., GREATLAND VILLAGE, CITY OF SAN PEDRO, LAGUNA';
        $contactNos = 'Tel. Nos. 8646 0519 / 8252 4884';

        $phpWord = new PhpWord;
        $section = $phpWord->addSection();

        // Header row: logos left & right with text in the middle (text wraps between images, starts at top)
        $logoLeftPath = public_path('img/logo.png');
        $logoRightPath = public_path('img/Seal_of_San_Pedro,_Laguna.png');
        $logoSize = ['width' => 80, 'height' => 80];
        $colLogoTwips = 1440;
        $colCenterTwips = 5500;

        $table = $section->addTable([
            'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED,
            'width' => 9500,
            'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::TWIP,
        ]);
        $row = $table->addRow();
        $cellLeft = $row->addCell($colLogoTwips, ['valign' => 'center']);
        $cellCenter = $row->addCell($colCenterTwips, ['valign' => 'center']);
        $cellRight = $row->addCell($colLogoTwips, ['valign' => 'center']);
        if (file_exists($logoLeftPath)) {
            $cellLeft->addTextRun(['alignment' => Jc::START])->addImage($logoLeftPath, $logoSize);
        }

        $fontstyle=[
            'name' => 'Cambria',
            'size' => 10,
            'bold' => true,
        ];

        $paragraphStyle = [
            'spaceAfter' => 0,
            'alignment' => Jc::CENTER,
        ];

        $cellCenter->addText('REPUBLIKA NG PILIPINAS', $fontstyle, $paragraphStyle);
        $cellCenter->addText('LALAWIGAN NG LAGUNA', $fontstyle, $paragraphStyle);
        $cellCenter->addText('LUNGSOD NG SAN PEDRO', $fontstyle, $paragraphStyle);
        $cellCenter->addText($barangayName, ['name' => 'Cambria', 'size' => 16, 'bold' => true, 'shading' => ['fill' => '00FF00']], $paragraphStyle);

        $fontstyle=[
            'name' => 'Cambria',
            'size' => 8,
        ];

        $cellCenter->addText($barangayAddress, $fontstyle, $paragraphStyle);
        $cellCenter->addText($contactNos, $fontstyle, $paragraphStyle);

        $fontstyle=[
            'name' => 'Cambria',
            'size' => 18,
            'bold' => true,
        ];

        $paragraphStyle = [
            'spaceBefore'=> 200,
            'spaceAfter' => 200,
            'alignment' => Jc::CENTER
        ];

        $cellCenter->addText('OFFICE OF THE PUNONG BARANGAY', $fontstyle, $paragraphStyle);
        if (file_exists($logoRightPath)) {
            $cellRight->addTextRun(['alignment' => Jc::END])->addImage($logoRightPath, $logoSize);
        }
        $section->addTextBreak(2);

        // Title
        $section->addText('NOTICE OF HEARING', ['bold' => true, 'size' => 14], ['spaceAftter' => 0, 'alignment' => Jc::CENTER]);
        $section->addText('(MEDIATION PROCEEDINGS)', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(1);


        // To / Address
        $section->addText('TO: ', ['size' => 12], ['spaceAfter' => 0]);
        $section->addText($blotter->respondent_name, ['size' => 12], ['spaceAfter' => 0]);
        $section->addText($blotter->respondent_address, ['size' => 12], ['spaceAfter' => 0]);
        $section->addTextBreak(3);

        // Body paragraph 1
        $body1 = "You are hereby required to appear before me on the {$formattedHearingDate} at {$blotter->hearing_datetime->format('g:i A')} o'clock at the {$blotter->hearing_venue} for a hearing of the complaint.";
        $section->addText($body1, ['size' => 12], ['alignment' => Jc::BOTH]);
        $section->addTextBreak(3);

        // Signatory (right-aligned)
        $section->addText($punongBarangay, ['bold' => true], ['alignment' => Jc::END]);
        $section->addText('PUNONG BARANGAY', [], ['alignment' => Jc::END]);
        $section->addTextBreak(2);

        $safeName = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $blotter->respondent_name);
        $filename = "Notice-of-Hearing-{$safeName}-" . now()->format('Y-m-d') . '.docx';

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

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
