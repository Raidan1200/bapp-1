<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Venue;
use App\Http\Requests\ZauberRequest;
use App\Http\Controllers\Api\NewOrderController;

// TODO: NewOrderController probably shouldn't be a Controller
//       but a Service-Class
class ZauberController extends NewOrderController
{
    public function order(ZauberRequest $request, Venue $venue)
    {
        return $this->newOrder($request, $venue);
    }

    protected function applyBookingRules(array $bookings)
    {
        $bookings = collect($bookings);

        $hpc = $bookings->firstWhere('package_name', 'Hüttenpaket Classic');
        $hpp = $bookings->firstWhere('package_name', 'Hüttenpaket Premium');
        $cur = $bookings->firstWhere('package_name', 'Curlingbahn');

        if ($hpc) {
            $hpc['deposit'] = 99;
        }

        if ($cur && !($hpc || $hpp)) {
            $cur['deposit'] = 100;
        }

        return array_filter([$hpc, $hpp, $cur]);
    }

    protected function applyOrderRules(Order $order)
    {
        $order->deposit_amount = ($deposit = $order->deposit);
        $order->interim_amount = $order->grossTotal - $deposit;

        return $order;
    }
}
