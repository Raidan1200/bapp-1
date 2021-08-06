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

    protected $listeners = ['updateOrder' => '$refresh'];

    public $bla;

    public function mount($order)
    {
        $this->order = $order;
        $this->selectedStatus = $order->status;
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

    public function getTotalProperty()
    {
        return $this->order->bookings->reduce(function ($sum, $booking) {
            return $sum += $booking->quantity * $booking->unit_price;
        });
    }

    public function getDepositProperty()
    {
        return $this->order->bookings->reduce(function ($deposit, $booking) {
            return $deposit += ($booking->quantity * $booking->unit_price) * ($booking->deposit / 100);
        });
    }

    public function render()
    {
        return view('livewire.order');
    }
}
