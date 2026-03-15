<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\CommitteeAssistantAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionsController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user && $user->isCommitteeHeadLike() && $user->official && $user->official->committee, 403, 'Unauthorized access.');

        $committeeSlug = $user->official->committee->slug;

        $assistants = User::role('assistant')->orderBy('name')->get();
        $assignments = CommitteeAssistantAccess::with('assistant')
            ->where('committee_head_id', $user->id)
            ->orderByDesc('id')
            ->get();

        return view('committees.permissions.index', compact('assistants', 'assignments', 'committeeSlug'));
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user && $user->isCommitteeHeadLike() && $user->official && $user->official->committee, 403, 'Unauthorized access.');

        $committeeSlug = $user->official->committee->slug;

        $validated = $request->validate([
            'assistant_user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $assistant = User::findOrFail($validated['assistant_user_id']);
        if (!$assistant->hasRole('assistant')) {
            return redirect()->route('committee.permissions.index')->with('error', 'Selected user is not an assistant.');
        }

        $existingForAssistant = CommitteeAssistantAccess::where('assistant_user_id', $assistant->id)->first();
        if ($existingForAssistant && (int) $existingForAssistant->committee_head_id !== (int) $user->id) {
            return redirect()->route('committee.permissions.index')
                ->with('error', 'This assistant is already assigned to another committee head.');
        }

        $access = CommitteeAssistantAccess::firstOrCreate([
            'assistant_user_id' => $assistant->id,
        ], [
            'committee_head_id' => $user->id,
            'committee_slug' => $committeeSlug,
        ]);

        if ((int) $access->committee_head_id !== (int) $user->id) {
            return redirect()->route('committee.permissions.index')
                ->with('error', 'This assistant is already assigned to another committee head.');
        }

        if ($access->committee_slug !== $committeeSlug) {
            $access->committee_slug = $committeeSlug;
            $access->save();
        }

        return redirect()->route('committee.permissions.index')->with('success', 'Assistant access granted successfully.');
    }

    public function destroy(CommitteeAssistantAccess $access)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user && $user->isCommitteeHeadLike() && $user->official && $user->official->committee, 403, 'Unauthorized access.');

        abort_unless((int) $access->committee_head_id === (int) $user->id, 403, 'Unauthorized access.');

        $access->delete();

        return redirect()->route('committee.permissions.index')->with('success', 'Assistant access revoked successfully.');
    }
}
