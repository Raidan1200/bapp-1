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
    public $selectedState;

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
        $this->selectedState = $order->state;
    }

    public function updated()
    {
        $this->dirty = true;
    }

    public function save()
    {
        $this->authorize('modify orders', $this->order);

        if ($stateTimestamps = $this->stateTimestamps()) {
            OrderStateChanged::dispatch($this->order, auth()->user(), $this->order->state, $this->selectedState);
        }

        $this->order->update(
            array_merge([
                'notes' => $this->notes,
                'state' => $this->selectedState,
            ], $stateTimestamps)
        );

        $this->dirty = false;
        $this->editingNote = false;
    }

    public function cancel()
    {
        $this->selectedState = $this->order->state;

        $this->dirty = false;
        $this->editingNote = false;
    }

    public function getColorProperty() : string
    {
        return $this->colors[$this->order->state] ?? '';
    }

    public function render()
    {
        return view('livewire.order');
    }

    // Helpers
    public function stateTimestamps() : array
    {
        if ($this->stateHasChanged()) {
            return $this->updatedTimestamps();
        }

        return [];
    }

    public function stateHasChanged() : bool
    {
        return $this->order->state !== $this->selectedState;
    }

    public function updatedTimestamps() : array
    {
        $timestamps = [];

        switch ($this->order->state) {
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
