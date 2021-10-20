<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InterimEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        // TODO EMAIL: JSON error
        // $pdf = (new Invoice)
        //     ->ofType('interim')
        //     ->forOrder($this->order)
        //     ->makePdf();

        $this
            ->from($this->order->venue->email)
            ->subject('Abschlussrechnung für ' . $this->order->venue->name)
            ->view('emails.interim');
    }
}
