<?php

namespace App\Http\Controllers\Api;

use App\Models\Room;
use App\Models\Order;
use App\Models\Venue;
use App\Models\Package;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ZauberRequest;
use App\Events\InvoiceEmailRequested;

class ZauberController extends Controller
{
    public function config()
    {
        // Note that auth()->user() is a Venue, not a User!
        return auth()->user()->load('rooms.packages');
    }

    public function bookings(Request $request, Room $room)
    {
        $from = Carbon::createFromDate(...explode('-', $request->input('from')))->hour(0)->minute(0)->second(0);
        $to = Carbon::createFromDate(...explode('-', $request->input('to')))->hour(23)->minute(59)->second(59);

        return $room->bookings()->where('starts_at', '<=', $to)->where('ends_at', '>=', $from)->get();
    }

    public function order(ZauberRequest $request, Venue $venue)
    {
        $order = $this->store($request->validated(), $venue);

        InvoiceEmailRequested::dispatch('deposit', $order->load('customer'));

        return $order;
    }

    protected function store(array $validated, Venue $venue)
    {
        return DB::transaction(function () use ($validated, $venue)
        {
            $customer = $this->createCustomer($validated['customer']);

            $order = $this->createOrder($venue, $validated['bookings'], $customer);

            $bookings = $this->makeBookings($validated['bookings']);

            $bookings = $this->applyBookingRules($bookings);
            $order->bookings()->createMany($bookings);

            $order = $this->applyOrderRules($order);

            $order->save();

            return $order;
        });
    }

    protected function createCustomer($customerData)
    {
        return Customer::create($customerData);
    }

    protected function createOrder($venue, $bookings, $customer)
    {
        $order = new Order;

        $order->invoice_id = rand();   // TODO ROLAND: How to generate?
        $order->state = 'fresh';
        $order->cash_payment = false;
        $order->venue_id = $venue->id;
        $order->starts_at = $this->firstBookingDate($bookings);
        $order->customer_id = $customer->id;

        $order->save();

        return $order;
    }

    protected function makeBookings($bookingData)
    {
        $bookings = [];

        foreach ($bookingData as $booking) {
            $package = Package::findOrFail($booking['package_id']);
            $booking['package_name'] = $package->name;
            $booking['unit_price'] = $package->unit_price;
            $booking['vat'] = $package->vat;
            $booking['deposit'] = $package->deposit;
            $booking['is_flat'] = $package->is_flat;
            $booking['snapshot'] = json_encode($this->packageSnapshot($package));
            $bookings[] = $booking;
        }

        return $bookings;
    }

    protected function applyBookingRules(array $bookings)
    {
        return $bookings;
    }

    protected function applyOrderRules(Order $order)
    {
        $order->deposit_amount = ($deposit = $order->deposit);
        $order->interim_amount = $order->grossTotal - $deposit;

        return $order;
    }

    protected function firstBookingDate($bookings)
    {
        return new Carbon(
            collect($bookings)
                ->pluck('starts_at')
                ->sort()
                ->values()
                ->first()
            );
    }

    protected function packageSnapshot($package)
    {
        return collect($package->toArray())->only(
            'id', 'name', 'slug',
            'unit_price', 'vat', 'deposit', 'is_flat',
            'price_flat', 'vat_flat', 'deposit_flat',
            'venue_id'
        );
    }
}
