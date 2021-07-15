<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueMemberController extends Controller
{
    public function store(Request $request, User $user)
    {
        $this->authorize('create venues');

        $request->validate([
            'venue' => 'required|exists:venues,id'
        ]);

        $user->venues()->attach($request->venue);

        return redirect()->back()->with('status', 'User added to Venue!');
    }

    public function destroy(Request $request, User $user, Venue $venue)
    {
        $this->authorize('modify users');

        $venue->users()->detach($user->id);

        return redirect()->back()->with('status', 'User removed to Venue!');
    }
}
