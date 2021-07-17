<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

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

    public function bookings(Room $room)
    {
        // TODO: Is it neccessary to check this? Is this handled by Sanctum???
        abort_if(auth()->user()->id !== $room->venue->id, 403);

        return $room->bookings;
    }

    public function store(Request $reuqest)
    {

    }
}
