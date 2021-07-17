<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueMemberController extends Controller
{
    // TODO: API is somewhat confusing. Pass Venue via URL, not as POST data
    public function store(Request $request, User $user)
    {
        $this->authorize('modify users');

        $request->validate([
            'venue' => 'required|exists:venues,id'
        ]);

        $user->venues()->attach($request->venue);

        return redirect()->back()->with('status', 'User added to Venue.');
    }

    public function destroy(Request $request, User $user, Venue $venue)
    {
        $this->authorize('modify users');

        $venue->users()->detach($user->id);

        return redirect()->back()->with('status', 'User removed from Venue.');
    }
}
