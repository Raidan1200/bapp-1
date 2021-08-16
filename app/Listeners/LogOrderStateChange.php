<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Order;
use App\Models\Action;
use App\Events\OrderStateChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogOrderStateChange
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderStateChanged $event)
    {
        Action::create([
            'user_name' => $event->user->name,
            'user_email' => $event->user->email,
            'message' => "state change: $event->from > $event->to",
            'order_id' => $event->order->id,
            'user_id' => $event->user->id,
        ]);
    }
}
