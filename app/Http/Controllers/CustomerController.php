<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function show(Customer $customer)
    {
        // LATER: duplicated from DashboardController ... DRY!!!
        $venues = auth()->user()->venues()->get();

        $paymentChecks = $venues->filter(
            fn($venue) => $venue->check_count > 0
        );

        // LATER: duplicated from DashboardController ... DRY!!!
        $newOrderCount = Order::where('state', 'fresh')
            ->whereBetween(
                'created_at',
                [now()->startOfDay()->subDays(1), now()]
            )->count();

        return view('customers.show', [
            'customer' => $customer,
            'orders' => $customer->orders,
            'paymentChecks' => $paymentChecks,
            'newOrderCount' => $newOrderCount
        ]);
    }
}
