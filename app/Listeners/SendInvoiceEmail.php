<?php

namespace App\Listeners;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Events\InvoiceEmailRequested;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInvoiceEmail // implements ShouldQueue
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

        // TODO: Handle invalid types ... try catch?
        //       throw new \Exception('Unknown email type: ' . $event->type);
        $emailClass = '\\App\\Mail\\' . ucfirst($event->type) . 'Email';
        $email_sent_field = $event->type . '_email_at';

        // TODO TODO: Attach Invoice!!!

        // TODO: Handle mail-sent errors ... try catch?
        $email->send(new $emailClass($event->order));

        $event->order->update([
            $email_sent_field => Carbon::now()
        ]);

    }
}
