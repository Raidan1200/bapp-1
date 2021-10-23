<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InterimEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdf;

    public function __construct(Order $order, string $pdf)
    {
        $this->order = $order;
        $this->pdf = $pdf;
    }

    public function build()
    {
        $this
            ->from($this->order->venue->email)
            ->subject('Abschlussrechnung für ' . $this->order->venue->name)
            ->attachData($this->pdf, 'rechnung-'.$this->order->interim_invoice_id.'.pdf', [
                'mime' => 'application/pdf',
            ])
            ->view('emails.interim');
    }
}
