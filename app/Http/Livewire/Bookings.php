<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use App\Models\Package;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Bookings extends Component
{
    use AuthorizesRequests;

    public $bookings;

    public $data;

    public $order;

    public bool $editing = false;

    public $foundPackages = [];
    public $row;

    public $rules = [
        'bookings.*.id' => 'nullable',
        'bookings.*.starts_at' => 'required|date',
        'bookings.*.ends_at' => 'required|date',
        'bookings.*.package_name' => 'required|string|max:255',
        'bookings.*.is_flat' => 'required',
        'bookings.*.quantity' => 'required|integer',
        'bookings.*.unit_price' => 'required|numeric',
        'bookings.*.vat' => 'required|numeric',
        'bookings.*.deposit' => 'required|numeric',
        'bookings.*.state' => 'required|in:stored,new,delete',
    ];

    public $validationAttributes = [
        'bookings.*.package_name' => 'Paketname',
        'bookings.*.starts_at' => 'Start',
        'bookings.*.ends_at' => 'Ende',
        'bookings.*.unit_price' => 'Bruttopreis',
        'bookings.*.vat' => 'MwSt',
        'bookings.*.quantity' => 'Menge',
        'bookings.*.deposit' => 'Anzahlung',
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
        $this->authorize('admin orders');

        $this->validate();

        $newBookings = [];

        // LATER This is REAAAALLY inefficient
        //       Does Laravel have something like "bulkUpdate" or "updateMany"?

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
                $booking['order_id'] = $this->order->id;

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
            'package_name' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'vat' => 20,
            'deposit' => 0,
            'is_flat' => false,
            'package_id' => null,
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

    public function updating($row, $search)
    {
        $this->row = str_replace(['bookings.', '.package_name'], ['', ''], $row);

        if (mb_strlen($search) >= 3) {
            $this->foundPackages = $this->findPackages($search);
        } else {
            $this->foundPackages = [];
        }
    }

    public function findPackages($search) {
        return Package::where('venue_id', $this->order->venue_id)
            ->where('name', 'like', "%{$search}%")
            ->orderBy('name')
            ->get();
    }

    public function fillFields($key, $package)
    {
        $this->bookings[$key] = [
            'package_name' => $package['name'],
            'starts_at' => '',
            'ends_at' => '',
            'quantity' => 1,
            'unit_price' => $package['unit_price'],
            'vat' => $package['vat'],
            'deposit' => 0,
            'is_flat' => false,
            'package_id' => $package['id'],
            'room_id' => null, // TODO: $package['roomId'] ... NO IDEA!!!
            'state' => 'new'
        ];

        $this->foundPackages = [];
    }

    public function render()
    {
        return view('livewire.bookings');
    }
}
