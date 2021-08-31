<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;

class ZauberInvoice
{
    public function prepareData(string $type, Order $order) : array
    {
        $items = [];

        foreach ($order->bookings as $booking) {
            $items[] = $booking->toArray();
        }

        return [
            'items' => $items,
            'total' => 12345,
        ];
    }
}
