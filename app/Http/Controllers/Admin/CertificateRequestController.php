<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateRequest;
use Illuminate\Http\Request;

class CertificateRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = CertificateRequest::with('resident.household')->orderByDesc('submitted_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(15)->withQueryString();

        return view('admin.certificate-requests.index', compact('requests'));
    }

    public function show(CertificateRequest $certificate_request)
    {
        $certificate_request->load('resident.household');

        return view('admin.certificate-requests.show', compact('certificate_request'));
    }

    public function update(Request $request, CertificateRequest $certificate_request)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,approved,released'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $certificate_request->remarks = $validated['remarks'] ?? $certificate_request->remarks;
        $certificate_request->status = $validated['status'];

        if ($validated['status'] === 'approved') {
            $certificate_request->approved_at = $certificate_request->approved_at ?? now();
            $certificate_request->approved_by = $certificate_request->approved_by ?? auth()->id();
        }
        if ($validated['status'] === 'released') {
            $certificate_request->released_at = now();
        }

        $certificate_request->save();

        return redirect()
            ->route('admin.certificate-requests.show', $certificate_request)
            ->with('success', 'Certificate request updated.');
    }
}
