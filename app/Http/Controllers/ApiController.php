<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
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
        // TODO: Is it neccessary to check this? Is this handled by Sanctum???
        abort_if(auth()->user()->id !== $room->venue->id, 403);

        $from = Carbon::createFromDate(...explode('-', $request->input('from')))->hour(0)->minute(0)->second(0);
        $to = Carbon::createFromDate(...explode('-', $request->input('to')))->hour(23)->minute(59)->second(59);

        return $room->bookings()->where('starts_at', '<', $to)->where('ends_at', '>', $from)->get();
    }

    public function order(Request $request)
    {
        return [$request->toArray()];
    }
}
