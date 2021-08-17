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
    public $latestAction;

    public $editingNote = false;
    public $dirty = false;

    protected $listeners = [
        'updateBookings' => '$refresh',
        'updateCustomer' => '$refresh'
    ];

    public function mount($order)
    {
        $this->order = $order;
        $this->notes = $order->notes;
        $this->selectedStatus = $order->status;
        $this->latestAction = $order->latestAction;
    }

    public function updated()
    {
        $this->dirty = true;
    }

    public function save()
    {
        $this->authorize('modify orders', $this->order);

        $oldStatus = $this->order->status;
        $status = [];

        if ($this->order->status !== $this->selectedStatus) {
            switch ($this->order->status) {
                case 'fresh':
                    $status['deposit_paid_at'] = null;
                    $status['interim_paid_at'] = null;
                    $status['final_paid_at'] = null;
                    break;

                case 'deposit_paid':
                    $status['deposit_paid_at'] = Carbon::now();
                    $status['interim_paid_at'] = null;
                    $status['final_paid_at'] = null;
                    break;

                case 'interim_paid':
                    $status['interim_paid_at'] = Carbon::now();
                    $status['final_paid_at'] = null;
                    break;

                case 'final_paid':
                    $status['final_paid_at'] = Carbon::now();
                    break;
            }

            OrderStateChanged::dispatch($this->order, auth()->user(), $oldStatus, $this->selectedStatus);
        }

        $this->order->update(
            array_merge([
                'notes' => $this->notes,
                'status' => $this->selectedStatus,
            ], $status)
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

    public function render()
    {
        return view('livewire.order');
    }
}