<?php

namespace App\Http\Livewire;

use App\Models\Venue;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VenueMember extends Component
{
    use AuthorizesRequests;

    public $user;
    protected $venues;

    public function render()
    {
        return view('livewire.venue-member', [
            'user' => $this->user,
            'venues' => Venue::whereNotIn('id', $this->user->venues->pluck('id'))->get()
        ]);
    }

    public function add(Venue $venue)
    {
        $this->authorize('modify users');

        $this->user->venues()->attach($venue->id);
        $this->user->refresh();
    }

    public function remove(Venue $venue)
    {
        $this->authorize('modify users');

        $this->user->venues()->detach($venue->id);
        $this->user->refresh();
    }
}
