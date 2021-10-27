<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    protected $colors = [
        'fresh' => 'border-gray-400',
        'overdue' => 'border->red->400',
        'deposit_paid' => 'border-yellow-500',
        'interim_paid' => 'border-green-500',
        'final_paid' => 'border-blue-500',
        'cancelled' => 'border-gray-400',
        'not_paid' => 'border-gray-400',
    ];

    public function show(Order $order)
    {
        $this->authorize('admin orders');

        return new OrderResource($order);
    }

    public function edit(Order $order)
    {
        $this->authorize('admin orders');

        return view('orders.edit', [
            'order' => $order,
        ]);
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete orders');

        $order->delete();

        return back()->with('status', 'Bestellung wurde gelöscht.');
    }

    // TODO API
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validated = $request->validated();

        $order->update($validated);

        $newBookings = [];

        foreach ($validated['bookings'] as $booking) {
            if ($booking['data']['id']) {
                if ($booking['state'] === 'stored') {
                    Booking::where('id', $booking['data']['id'])->update($booking['data']);
                } elseif ($booking['state'] === 'delete') {
                    Booking::where('id', $booking['data']['id'])->delete();
                }
            } else {
                $newBookings[] = $order->bookings()->create($booking['data']);
            }
        }

        return response()->json(['newBookings' => $newBookings]);
    }
}
