<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use Livewire\Component;

class Bookings extends Component
{
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
        'bookings.*.status' => 'required|in:stored,new,delete',
    ];

    public function mount()
    {
        foreach ($this->bookings as &$booking) {
            $booking['status'] = 'stored';
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
        $this->validate();

        // TODO This is REAAAALLY inefficient
        //      Does Laravel have something like "bulkUpdate" or "updateMany"?

        $newBookings = [];

        foreach ($this->bookings as $booking) {
            if ($booking['status'] === 'delete' || $booking['status'] === 'stored') {
                $model = Booking::find($booking['id']);

                if ($booking['status'] === 'delete') {
                    $model->delete();
                }

                if ($booking['status'] === 'stored') {
                    $model->update($booking);
                    $newBookings[] = $booking;
                }
            }

            if ($booking['status'] === 'new') {
                $booking['order_id'] = $this->orderId;

                $newBooking = Booking::create($booking);
                $booking['id'] = $newBooking->id;
                $booking['status'] = 'stored';

                $newBookings[] = $booking;
            }
        }

        $this->editing = false;
        $this->bookings = $newBookings;
        $this->data = $newBookings;
        $this->emit('updateOrder');
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
            'status' => 'new'
        ];
    }

    public function removeRow($key)
    {
        $this->resetValidation();

        switch ($this->bookings[$key]['status']) {
            case 'stored':
                $this->bookings[$key]['status'] = 'delete';
                break;

            case 'delete':
                $this->bookings[$key]['status'] = 'stored';
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
