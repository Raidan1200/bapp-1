<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Order extends Component
{
    use AuthorizesRequests;

    // TODO: Ist das schlau, das mit Livewire zu machen
    //       Dadurch lädt die Seite ja nicht neu,
    //       d.h. andere Mitarbeiter sehen potenziell alte Daten
    // CHROMIUM!!!, 3 iPads

    public $order;
    public $selectedStatus;

    public $dirty = false;

    public function mount($order)
    {
        $this->order = $order;
        $this->selectedStatus = $order->status;
    }

    public function render()
    {
        return view('livewire.order');
    }

    public function updated()
    {
        $this->dirty = true;
    }

    public function save()
    {
        $this->authorize('modify orders', $this->order);

        $this->order->update([
            'status' => $this->selectedStatus
        ]);

        $this->dirty = false;
    }

    public function nope()
    {
        $this->selectedStatus = $this->order->status;

        $this->dirty = false;
    }
}
