<?php

namespace App\Http\Controllers\Api;

use App\Models\Room;
use App\Models\Venue;
use App\Models\Package;
use App\Events\NewOrder;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ZauberRequest;
use Illuminate\Support\Facades\Validator;

class ZauberController extends Controller
{
    public function config()
    {
        // Note that auth()->user() is a Venue, not a User!
        return auth()->user()->load('rooms.packages');
    }

    public function bookings(Request $request, Room $room)
    {
        abort_if(auth()->user()->id !== $room->venue->id, 403);

        $from = Carbon::createFromDate(...explode('-', $request->input('from')))->hour(0)->minute(0)->second(0);
        $to = Carbon::createFromDate(...explode('-', $request->input('to')))->hour(23)->minute(59)->second(59);

        return $room->bookings()->where('starts_at', '<=', $to)->where('ends_at', '>=', $from)->get();
    }

    public function order(ZauberRequest $request, Venue $venue)
    {
        $validated = $request->validated();

        $firstBookingDate = collect($validated['bookings'])
            ->pluck('starts_at')
            ->sort()
            ->values()
            ->first();

        $bookings = [];

        // TODO: This is actually an "n + 1" query, but I guess it's OK
        foreach ($validated['bookings'] as $booking) {
            $package = package::findOrFail($booking['package_id']);
            $booking['package_name'] = $package->name;
            $booking['unit_price'] = $package->unit_price;
            $booking['vat'] = $package->vat;
            $booking['deposit'] = $package->deposit;
            $booking['is_flat'] = $package->is_flat;
            $booking['snapshot'] = json_encode($this->packageSnapshot($package));
            $bookings[] = $booking;
        }

        $order = DB::transaction(function () use ($validated, $venue, $firstBookingDate, $bookings) {
            $customer = Customer::create($validated['customer']);

            $order = $customer->orders()->create([
                'invoice_id' => rand(), // TODO ROLAND
                'state' => 'fresh',
                'cash_payment' => rand(0, 1), // TODO ROLAND
                'deposit' => 0,
                'venue_id' => $venue->id,
                'starts_at' => new Carbon($firstBookingDate),
            ]);

            $order->bookings()->createMany($bookings);

            return $order;
        });

        // TODO: Maybe I should populate 'deposit_amount' and 'interim_amount' here???
        //       Yes. YES!!!

        // TODO: A Model-Observer might be a better solution?
        // OTOH, we probably need a mail-queue for batch processing anyways
        NewOrder::dispatch($order->load('customer'));

        return $order;
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
