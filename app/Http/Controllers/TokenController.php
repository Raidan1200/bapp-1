<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function store(Venue $venue)
    {
        $token = $venue->createToken('api-token')->plainTextToken;

        return back()->with(['status' => $token]);
    }
}
