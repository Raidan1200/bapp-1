<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use Livewire\Component;
use App\Events\OrderHasChanged;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Bookings extends Component
{
    use AuthorizesRequests;

    public $bookings;

    public $data;

    public $orderId;

    public bool $editing = false;

    public $rules = [
        'bookings.*.id' => 'nullable',
        'bookings.*.starts_at' => 'nullable|date',
        'bookings.*.ends_at' => 'nullable|date',
        'bookings.*.product_name' => 'required|string|max:255',
        'bookings.*.is_flat' => 'required',
        'bookings.*.quantity' => 'required|numeric',
        'bookings.*.unit_price' => 'required|numeric',
        'bookings.*.vat' => 'required|numeric',
        'bookings.*.deposit' => 'required|numeric',
        'bookings.*.state' => 'required|in:stored,new,delete',
    ];

    public function mount()
    {
        foreach ($this->bookings as &$booking) {
            $booking['state'] = 'stored';
            $booking['is_flat'] = $booking['is_flat'] ?? false;
        }

        $this->data = $this->bookings;
    }

    public function startEditing()
    {
        $this->editing = true;
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->bookings = $this->data;
        $this->editing = false;
    }

    public function save()
    {
        $this->authorize('modify orders');

        $this->validate();

        // TODO IMPORTANT:
        //      Check if the new quantiy exceeds the capacity!

        // TODO This is REAAAALLY inefficient
        //      Does Laravel have something like "bulkUpdate" or "updateMany"?

        foreach ($this->bookings as $booking) {
            if ($booking['state'] === 'delete' || $booking['state'] === 'stored') {
                $model = Booking::find($booking['id']);

                if ($booking['state'] === 'delete') {
                    $model->delete();
                }

                if ($booking['state'] === 'stored') {
                    $model->update($booking);
                    $newBookings[] = $booking;
                }
            }

            if ($booking['state'] === 'new') {
                $booking['order_id'] = $this->orderId;

                $newBooking = Booking::create($booking);
                $booking['id'] = $newBooking->id;
                $booking['state'] = 'stored';

                $newBookings[] = $booking;
            }
        }

        $this->editing = false;
        $this->bookings = $newBookings;
        $this->data = $newBookings;
        $this->emitUp('updateBookings');
    }

    public function addRow()
    {
        $this->resetValidation();

        $this->bookings[] = [
            'starts_at' => null,
            'ends_at' => null,
            'product_name' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'vat' => 20,
            'deposit' => 0,
            'is_flat' => false,
            'product_id' => null,
            'room_id' => null,
            'state' => 'new'
        ];
    }

    public function removeRow($key)
    {
        $this->resetValidation();

        switch ($this->bookings[$key]['state']) {
            case 'stored':
                $this->bookings[$key]['state'] = 'delete';
                break;

            case 'delete':
                $this->bookings[$key]['state'] = 'stored';
                break;

            case 'new':
                array_splice($this->bookings, $key, 1);
                break;
        }
    }

    public function render()
    {
        return view('livewire.bookings');
    }
}
