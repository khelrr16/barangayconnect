<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\CertificateRequest;
use App\Models\ResidentLinkVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function index()
    {
        $resident = auth()->user()->resident;
        $household = $resident?->household;
        $pendingRequestsCount = $resident
            ? CertificateRequest::where('resident_id', $resident->id)->where('status', 'pending')->count()
            : 0;
        $householdMembersCount = $household ? $household->residents()->count() : 0;
        $latestAnnouncement = Announcement::published()->orderByDesc('published_at')->first();

        return view('resident.dashboard', compact(
            'resident',
            'household',
            'pendingRequestsCount',
            'householdMembersCount',
            'latestAnnouncement'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        $resident = $user->resident;

        if($resident) {
            $resident->load('household');
        } else {
            $pendingVerification = $user->residentLinkVerifications()->where('status', 'pending')->latest()->first();
            $verifications = $user->residentLinkVerifications()->get();
        }

        return view('resident.profile', compact('resident', 'pendingVerification', 'verifications'));
    }

    public function sendVerification(Request $request)
    {
        $user = auth()->user();
        if ($user->resident_id) {
            return redirect()->route('resident.profile')->with('info', 'Your account is already linked.');
        }
        if ($user->residentLinkVerifications()->where('status', 'pending')->exists()) {
            return redirect()->route('resident.profile')->with('info', 'You already have a pending verification. Please wait for the admin to review.');
        }

        $validated = $request->validate([
            'verification_id' => ['required', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'], // 5MB
        ], [
            'verification_id.required' => 'Please upload your Philippine National ID or valid ID.',
            'verification_id.mimes' => 'File must be an image (JPEG, PNG) or PDF.',
            'verification_id.max' => 'File must not exceed 5MB.',
        ]);

        $path = $request->file('verification_id')->store('verification-ids', 'public');

        ResidentLinkVerification::create([
            'user_id' => $user->id,
            'document_path' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('resident.profile')->with('success', "You've sent a verification ID. Please wait for the admin to link your profile.");
    }

    public function requestDocument()
    {
        $resident = auth()->user()->resident;
        $certificateTypes = CertificateRequest::TYPES;

        return view('resident.request-document', compact('resident', 'certificateTypes'));
    }

    public function storeDocumentRequest(Request $request)
    {
        $user = auth()->user();
        if (!$user->resident_id) {
            return redirect()->route('resident.request-document')
                ->with('error', 'Link your resident profile to request documents.');
        }

        $validated = $request->validate([
            'certificate_type' => ['required', 'string', 'in:' . implode(',', array_keys(CertificateRequest::TYPES))],
            'purpose' => ['required', 'string', 'max:1000'],
            'priority' => ['nullable', 'string', 'in:normal,urgent'],
        ]);

        CertificateRequest::create([
            'resident_id' => $user->resident_id,
            'certificate_type' => $validated['certificate_type'],
            'purpose' => $validated['purpose'],
            'priority' => $validated['priority'] ?? 'normal',
        ]);

        return redirect()->route('resident.my-requests')->with('success', 'Your document request has been submitted.');
    }

    public function myRequests()
    {
        $resident = auth()->user()->resident;
        $requests = $resident
            ? CertificateRequest::where('resident_id', $resident->id)->orderByDesc('submitted_at')->get()
            : collect();

        return view('resident.my-requests', compact('requests', 'resident'));
    }

    public function announcements()
    {
        $announcements = Announcement::published()->orderByDesc('published_at')->paginate(10);

        return view('resident.announcements', compact('announcements'));
    }

    public function contact()
    {
        return view('resident.contact');
    }
}
