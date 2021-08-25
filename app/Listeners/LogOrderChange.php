<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Order;
use App\Models\Action;
use App\Events\OrderHasChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogOrderChange
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
    public function handle(OrderHasChanged $event)
    {
        Action::create([
            'user_name' => $event->user->name,
            'user_email' => $event->user->email,
            'what' => $event->what,
            'from' => $event->from,
            'to' => $event->to,
            'order_id' => $event->order->id,
            'user_id' => $event->user->id,
        ]);
    }
}
