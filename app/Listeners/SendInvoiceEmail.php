<?php

namespace App\Listeners;

use App\Mail\FinalEmail;
use App\Mail\DepositEmail;
use App\Mail\InterimEmail;
use App\Mail\CancelledEmail;
use App\Events\InvoiceEmailRequested;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInvoiceEmail implements ShouldQueue
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
    public function handle(InvoiceEmailRequested $event)
    {
        $email = Mail::to($event->order->customer->email);

        switch ($event->type) {
            case 'deposit':
                $email->send(new DepositEmail($event->order));
                break;
            case 'interim':
                $email->send(new InterimEmail($event->order));
                break;
            case 'final':
                $email->send(new FinalEmail($event->order));
                break;
            case 'cancelled':
                $email->send(new CancelledEmail($event->order));
                break;
                default:
                    throw new \Exception('Unknown email type: ' . $event->type);
        }
    }
}
