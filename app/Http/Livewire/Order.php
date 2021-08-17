<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Events\OrderStateChanged;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Order extends Component
{
    use AuthorizesRequests;

    public $order;

    public $notes;
    public $selectedStatus;

    public $dirty = false;

    protected $colors = [
        'fresh' => 'border-red-500',
        'cancelled' => 'border-red-500',
        'deposit_paid' => 'border-yellow-500',
        'interim_paid' => 'border-blue-500',
        'final_paid' => 'border-green-500',
    ];

    protected $listeners = [
        'updateBookings' => '$refresh',
        'updateCustomer' => '$refresh'
    ];

    public function mount($order)
    {
        $this->order = $order;
        $this->notes = $order->notes;
        $this->selectedStatus = $order->status;
    }

    public function updated()
    {
        $this->dirty = true;
    }

    public function save()
    {
        $this->authorize('modify orders', $this->order);

        if ($statusTimestamps = $this->statusTimestamps()) {
            OrderStateChanged::dispatch($this->order, auth()->user(), $this->order->status, $this->selectedStatus);
        }

        $this->order->update(
            array_merge([
                'notes' => $this->notes,
                'status' => $this->selectedStatus,
            ], $statusTimestamps)
        );

        $this->dirty = false;
        $this->editingNote = false;
    }

    public function cancel()
    {
        $this->selectedStatus = $this->order->status;

        $this->dirty = false;
        $this->editingNote = false;
    }

    // TODO: This is duplicated in DepositEmail.php !!! BAD!
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

    public function getColorProperty() : string
    {
        return $this->colors[$this->order->status] ?? '';
    }

    public function render()
    {
        return view('livewire.order');
    }

    // Helpers
    public function statusTimestamps() : array
    {
        if ($this->statusHasChanged()) {
            return $this->updatedTimestamps();
        }

        return [];
    }

    public function statusHasChanged() : bool
    {
        return $this->order->status !== $this->selectedStatus;
    }

    public function updatedTimestamps() : array
    {
        $timestamps = [];

        switch ($this->order->status) {
            case 'fresh':
                $timestamps['deposit_paid_at'] = null;
                $timestamps['interim_paid_at'] = null;
                $timestamps['final_paid_at'] = null;
                break;

            case 'deposit_paid':
                $timestamps['deposit_paid_at'] = Carbon::now();
                $timestamps['interim_paid_at'] = null;
                $timestamps['final_paid_at'] = null;
                break;

            case 'interim_paid':
                $timestamps['interim_paid_at'] = Carbon::now();
                $timestamps['final_paid_at'] = null;
                break;

            case 'final_paid':
                $timestamps['final_paid_at'] = Carbon::now();
                break;
        }

        return $timestamps;
    }
}
