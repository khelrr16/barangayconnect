<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\ResidentLinkVerification;
use Illuminate\Http\Request;

class ResidentLinkVerificationController extends Controller
{
    public function index()
    {
        $verifications = ResidentLinkVerification::with('user')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.verification-requests.index', compact('verifications'));
    }

    public function show(ResidentLinkVerification $verification)
    {
        $verification->load('user');
        $residents = Resident::orderBy('last_name')->orderBy('first_name')->get();

        return view('admin.verification-requests.show', compact('verification', 'residents'));
    }

    public function approve(Request $request, ResidentLinkVerification $verification)
    {
        if ($verification->status !== 'pending') {
            return redirect()->route('admin.verification-requests.index')
                ->with('error', 'This request was already processed.');
        }

        $validated = $request->validate([
            'resident_id' => ['required', 'exists:residents,id'],
        ]);

        $user = $verification->user;
        $user->update(['resident_id' => $validated['resident_id']]);

        $verification->update([
            'status' => 'approved',
            'resident_id' => $validated['resident_id'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'remarks' => $request->input('remarks'),
        ]);

        return redirect()->route('admin.verification-requests.index')
            ->with('success', 'User has been linked to the resident. The resident can now use the full portal.');
    }

    public function reject(Request $request, ResidentLinkVerification $verification)
    {
        if ($verification->status !== 'pending') {
            return redirect()->route('admin.verification-requests.index')
                ->with('error', 'This request was already processed.');
        }

        $verification->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'remarks' => $request->input('remarks'),
        ]);

        return redirect()->route('admin.verification-requests.index')
            ->with('success', 'Verification request has been rejected. The user may submit a new ID.');
    }
}
