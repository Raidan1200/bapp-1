<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;

class ZauberInvoice
{
    // TODO: handle()? process()?
    public function prepareData(string $type, Order $order) : array
    {
        $vat = [];
        $net_total = 0;
        $gross_total = 0;

        foreach ($order->bookings as $booking) {
            if (isset($vat[$booking->vat])) {
                $vat[$booking->vat] += $booking->vatAmount;
            } else {
                $vat[$booking->vat] = $booking->vatAmount;
            }

            $net_total += $booking->netTotal;
            $gross_total += $booking->grossTotal;
        }

        foreach ($order->items as $item) {
            if (isset($vat[$item->vat])) {
                $vat[$item->vat] += $item->vatAmount;
            } else {
                $vat[$item->vat] = $item->vatAmount;
            }

            $net_total += $item->netTotal;
            $gross_total += $item->grossTotal;
        }

        foreach ($vat as &$value) {
            $value = round($value);
        }

        return [
            'vat' => $vat,
            'net_total' => round($net_total),
            'gross_total' => round($gross_total),
            'gross_total2' => $order->grossTotal, // TODO: Remove, this is just for testing
        ];
    }
}
