<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $orders = Order::with('bookings')->get();

        return view('dashboard', [
            'orders' => $orders
        ]);
    }
}
