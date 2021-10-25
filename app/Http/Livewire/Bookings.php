<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Booking;
use App\Models\Package;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Bookings extends Component
{
    use AuthorizesRequests;

    public $order;

    public bool $editing = false;

    public $foundPackages = [];
    public $row;

    public $rules = [
        'bookings.*.state' => 'required|in:stored,new,delete',
        'bookings.*.starts_time' => 'nullable|date_format:H:i',
        'bookings.*.ends_time' => 'nullable|date_format:H:i',

        'bookings.*.data.id' => 'nullable',
        'bookings.*.data.package_name' => 'required|string|max:255',
        'bookings.*.data.is_flat' => 'required',
        'bookings.*.data.quantity' => 'required|integer',
        'bookings.*.data.unit_price' => 'required|numeric',
        'bookings.*.data.vat' => 'required|numeric',
        'bookings.*.data.deposit' => 'required|numeric',
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

    public function mount(Order $order)
    {
        $this->order = $order;

        $this->bookings = $this->getBookings($order);
    }

    public function render()
    {
        return view('livewire.bookings');
    }

    public function startEditing()
    {
        $this->editing = true;
    }

    public function cancel()
    {
        $this->bookings = $this->getBookings();
        $this->editing = false;
    }

    public function save()
    {
        $this->authorize('modify bookings');
        $this->validate();

        foreach ($this->bookings as $idx => &$booking) {
            $data = &$booking['data'];
            $data['starts_at'] = $this->setTime($booking['starts_time']);
            $data['ends_at'] = $this->setTime($booking['ends_time']);

            if ($booking['state'] === 'delete' || $booking['state'] === 'stored') {
                $model = Booking::find($booking['data']['id']);

                if ($booking['state'] === 'delete') {
                    $model->delete();
                    array_splice($this->bookings, $idx, 1);
                }

                if ($booking['state'] === 'stored') {
                    $model->update($booking['data']);
                }
            }

            if ($booking['state'] === 'new') {
                $model = Booking::create($booking['data']);
                $booking['data']['id'] = $model->id;
                $booking['state'] = 'stored';
            }
        }

        $this->editing = false;
        $this->emitUp('updateBookings');
    }

    public function addRow()
    {
        $this->resetValidation();

        $booking = $this->makeBooking();

        $this->bookings[] = $this->wrapBooking($booking, 'new');
    }

    public function toggleDelete($key)
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
        $this->row = str_replace(['bookings.', '.data.package_name'], ['', ''], $row);

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
        $this->bookings[$key]['data'] = $this->makeBooking([
            'id' => $this->bookings[$key]['data']['id'],
            'package_name' => $package['name'],
            'starts_at' => $this->order->starts_at,
            'ends_at' => $this->order->starts_at,
            'quantity' => 1,
            'unit_price' => $package['unit_price'],
            'vat' => $package['vat'],
            'deposit' => 0,
            'interval' => $package['interval'],
            'is_flat' => false,
            'package_id' => $package['id'],
            'room_id' => null, // TODO: $package['roomId'] ... NO IDEA!!!
        ]);

        $this->foundPackages = [];
    }

    protected function getBookings()
    {
        return $this->order->bookings->map(function ($booking) {
            return $this->wrapBooking($booking, 'stored');
        })->toArray();
    }

    protected function wrapBooking($booking, string $state)
    {
        return [
            'data' => $booking instanceof Booking ? $booking->toArray() : $booking,
            'state' => $state,
            'starts_time' => $this->screenDate($booking['starts_at']),
            'ends_time' => $this->screenDate($booking['ends_at']),
        ];
    }

    protected function screenDate($date)
    {
        return $date
            ? (new Carbon($date))->timezone('Europe/Berlin')->format('H:i')
            : '';
    }

    protected function setTime($time)
    {
        return $time
            ? Carbon::createFromTimeString($time, 'Europe/Berlin')
                ->setDateFrom($this->order->starts_at)
                ->setTimezone('UTC')
            : null;

    }

    protected function makeBooking(array $data = [])
    {
        return array_merge([
            'id' => null,
            'package_name' => '',
            'starts_at' => null,
            'ends_at' => null,
            'quantity' => 1,
            'unit_price' => 100,
            'vat' => 20,
            'deposit' => 0,
            'interval' => null,
            'is_flat' => false,
            'order_id' => $this->order->id,
            'package_id' => null,
            'room_id' => null,
        ], $data);
    }
}
