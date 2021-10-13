<?php

namespace App\Listeners;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Events\InvoiceEmailRequested;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// TODO TODO: To queue or not to queue?
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

        // TODO: Handle invalid types ... try catch here?
        //       throw new \Exception('Unknown email type: ' . $event->type);
        //       Or below on $email->send()?
        $emailClass = '\\App\\Mail\\' . ucfirst($event->type) . 'Email';
        $email_sent_field = $event->type . '_email_at';

        // TODO TODO: Attach Invoice!!!

        // TODO: How do I handle mail-sent errors ... try catch?
        $email->send(new $emailClass($event->order));

        // TODO: This does not belong in the Listener. Or does it?
        // TODO TODO: Are Cancelled Emails actually being sent?
        //            If yes, I need a "cancelled_email_at" field!
        if ($event->type !== 'cancelled') {
            $event->order->update([
                $email_sent_field => Carbon::now()
            ]);
        }
    }
}
