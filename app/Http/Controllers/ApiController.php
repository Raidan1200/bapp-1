<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Venue;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    // TODO IMPORTANT: Produkt mit Freitext und Freipreis hinzuf+gen

    // protected $rules = [
    //     'starts_at' => 'required|date',
    //     'ends_at' => 'required|date',
    //     'quantity' => 'required|integer',
    //     'room_id' => 'required|exists:rooms,id',
    //     'product_id' => 'required|products,id',
    // ];

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

        $validated = $request->validate([
            'customer.first_name' => 'required',
            'customer.last_name' => 'required',
            'customer.email' => 'required',
            'customer.company' => 'sometimes',
            'customer.street' => 'required',
            'customer.street_no' => 'required',
            'customer.zip' => 'required',
            'customer.city' => 'required',
            'customer.phone' => 'required',
        ]);

        $customer = Customer::create($validated['customer']);

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
        ]);

        foreach ($request['bookings'] as $index => $booking) {
            $validatedBooking = Validator::validate($booking, [
                'booking.starts_at' => 'required|date',
                'booking.ends_at' => 'required|date',
                'booking.quantity' => 'required|numeric',
                'productId' => 'exists:products,id',
                'roomId' => 'exists:rooms,id',
            ]);

            // TODO IMPORTANT: KNAUB!!!
            $validatedBooking['starts_at'] = $validatedBooking['booking']['starts_at'];
            $validatedBooking['ends_at'] = $validatedBooking['booking']['ends_at'];
            $validatedBooking['quantity'] = $validatedBooking['booking']['quantity'];
            $validatedBooking['product_id'] = $validatedBooking['productId'];
            $validatedBooking['room_id'] = $validatedBooking['roomId'];

            // TODO IMPORTANT: Inefficient
            $product = Product::find($validatedBooking['productId']);

            $validatedBooking['product_name'] = $product->name;
            $validatedBooking['unit_price'] = $product->unit_price;
            $validatedBooking['vat'] = $product->vat;
            // TODO IMPORTANT: Naming inconsitent: flat vs is_flat
            $validatedBooking['flat'] = $product->is_flat;
            $validatedBooking['product_snapshot'] = json_encode($product);

            $order->bookings()->create($validatedBooking);
        }

        // TODO IMPORTANT: Send deposit email

        return $order;
    }
}
