<?php

namespace App\Http\Livewire;

use App\Models\Package;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PackageRoom extends Component
{
    use AuthorizesRequests;

    public $room;

    public function render()
    {
        return view('livewire.package-room', [
            'assignedPackages' => $this->room->packages,
            'otherPackages' => $this->otherPackages()
        ]);
    }

    protected function otherPackages()
    {
        return $this->room->venue->packages()->whereNotIn('id', $this->room->packages->pluck('id'))->get();
    }

    public function add(Package $package)
    {
        $this->authorize('modify rooms');

        $this->room->packages()->attach($package->id);
        $this->room->refresh();
    }

    public function remove(Package $package)
    {
        $this->authorize('modify rooms');

        $this->room->packages()->detach($package->id);
        $this->room->refresh();
    }
}
