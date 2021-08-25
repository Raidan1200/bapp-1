<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function show(Customer $customer)
    {
        return view('customers.show', [
            'customer' => $customer,
            'orders' => $customer->orders
        ]);
    }
}
