<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function destroy(Order $order)
    {
        $this->authorize('delete orders');

        $order->delete();

        return back()->with('status', 'Bestellung wurde gelöscht.');
    }
}
