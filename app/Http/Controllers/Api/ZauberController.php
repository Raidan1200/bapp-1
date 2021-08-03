<?php

namespace App\Http\Controllers\Api;

use App\Models\Room;
use App\Models\Venue;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ZauberController extends Controller
{
    // TODO IMPORTANT: Produkt mit Freitext und Freipreis hinzuf+gen

    protected $rules = [
        'customer' => [
            'customer.first_name' => 'required',
            'customer.last_name' => 'required',
            'customer.email' => 'required',
            'customer.company' => 'sometimes',
            'customer.street' => 'required',
            'customer.street_no' => 'required',
            'customer.zip' => 'required',
            'customer.city' => 'required',
            'customer.phone' => 'required',
        ],
        'booking' => [
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
            'quantity' => 'required|numeric',
            'product_id' => 'exists:products,id',
            'room_id' => 'exists:rooms,id',
        ]
    ];

    public function config()
    {
        // Note that auth()->user() is a Venue, not a User!
        return auth()->user()->load('rooms.products');
    }

    public function bookings(Request $request, Room $room)
    {
        abort_if(auth()->user()->id !== $room->venue->id, 403);

        $from = Carbon::createFromDate(...explode('-', $request->input('from')))->hour(0)->minute(0)->second(0);
        $to = Carbon::createFromDate(...explode('-', $request->input('to')))->hour(23)->minute(59)->second(59);

        return $room->bookings()->where('starts_at', '<=', $to)->where('ends_at', '>=', $from)->get();
    }

    public function order(Request $request, Venue $venue)
    {
        // TODO IMPORTANT: This whole process should be a DB-transaction!!!

        $p1 = Product::find($request->bookings[0]['productId']); // TODO siehe unten

        $validated = $request->validate($this->rules['customer']);

        $customer = Customer::create($validated['customer']);

        $firstBookingDate = collect($request['bookings'])
            ->pluck('booking.starts_at')
            ->sort()
            ->values()
            ->first();

            // TODO validation
        $order = $customer->orders()->create([
            'invoice_id' => rand(), // TODO
            'status' => 'deposit_mail_sent',
            'cash_payment' => rand(0, 1), // TODO
            // TODO: Wenn jedes Produkt seinen eigenen Anzahlungs-Prozentsatz hat
            // Wie berechnet sich dann der Anzahlungs-Prozentsatz der Gesamten Rechnung???

            // Kunde Reserviert ... Nur Restaurant 10%
            // Curling Getränke Flat ...
            // Nur Curling ... 100%
            // Roland PDF Klass Reservation PHP

            'deposit' => $p1->deposit,
            'venue_id' => $venue->id,
            'starts_at' => new Carbon($firstBookingDate),
        ]);

        foreach ($request['bookings'] as $index => $booking) {
            // TODO IMPORTANT: KNAUB!!!
            $booking['starts_at'] = $booking['booking']['starts_at'];
            $booking['ends_at'] = $booking['booking']['ends_at'];
            $booking['quantity'] = $booking['booking']['quantity'];
            $booking['product_id'] = $booking['productId'];
            $booking['room_id'] = $booking['roomId'];

            $validatedBooking = Validator::validate($booking, $this->rules['booking']);

            // TODO IMPORTANT: Inefficient
            $product = Product::find($validatedBooking['product_id']);

            $validatedBooking['product_name'] = $product->name;
            $validatedBooking['unit_price'] = $product->unit_price;
            $validatedBooking['vat'] = $product->vat;
            // TODO IMPORTANT: Naming inconsitent: flat vs is_flat
            $validatedBooking['is_flat'] = $product->is_flat;
            $validatedBooking['snapshot'] = json_encode($product);

            $order->bookings()->create($validatedBooking);
        }

        // TODO IMPORTANT: Send deposit email

        return $order;
    }
}
