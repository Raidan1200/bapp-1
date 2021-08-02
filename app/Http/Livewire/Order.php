<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Order extends Component
{
    use AuthorizesRequests;

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

    public function cancel()
    {
        $this->selectedStatus = $this->order->status;

        $this->dirty = false;
    }
}
