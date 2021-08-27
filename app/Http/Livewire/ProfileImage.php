<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProfileImage extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public $image;
    public $user;
    public $idHack = 0;

    // TODO: Test
    public function save()
    {
        if ($this->user->id !== auth()->id()) {
            $this->authorize('modify users');
        }

        $this->validate([
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);

        if ($this->user->image) {
            Storage::delete($this->user->image);
        }

        $path = $this->image->store('profileimages');

        $this->user->update([
            'image' => $path,
        ]);

        $this->idHack++;
    }

    public function render()
    {
        return view('livewire.profile-image');
    }

    public function delete()
    {
        if ($this->user->id !== auth()->id()) {
            $this->authorize('modify users');
        }

        if ($this->user->image) {
            Storage::delete($this->user->image);
        }

        $this->user->update([
            'image' => null,
        ]);

        $this->idHack++;
    }
}
