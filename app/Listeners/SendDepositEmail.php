<?php

namespace App\Listeners;

use App\Mail\DepositEmail;
use App\Events\OrderReceived;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDepositEmail implements ShouldQueue
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
    public function handle(OrderReceived $event)
    {
        Mail::to($event->order->customer->email)
            ->send(new DepositEmail($event->order));
    }
}
