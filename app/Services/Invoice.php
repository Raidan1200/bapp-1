<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;
use App\Services\Pdf;

class Invoice
{
    public $invoiceId;
    public $type = '';
    public $date;
    public $order;
    public $subject = '';
    public $text = [];

    private $dest = 'I';

    protected $types = ['deposit', 'interim', 'final', 'cancelled'];
    protected $updatedFields = [];

    public function ofType(string $type)
    {
        if (!in_array($type, $this->types)) {
            throw new \Exception('Unknown Invoice Type: ' . $this->type ?? 'null');
        }

        $this->type = $type;
        return $this;
    }

    public function forOrder(Order $order)
    {
        $this->order = $order;
        $this->setDate();
        $this->setInvoiceId();
        $this->setSubject();
        $this->setText();

        return $this;
    }

    public function updatedFields()
    {
        return $this->updatedFields;
    }

    public function invoiceId()
    {
        return $this->invoiceId;
    }

    public function asString()
    {
        $this->dest = 'S';
        return $this;
    }

    public function asStream()
    {
        $this->dest = 'I';
        return $this;
    }

    public function makePdf()
    {
        return (new Pdf($this))->output($this->dest);
    }

    protected function setDate()
    {
        $at_field = $this->type . '_invoice_at';

        $this->date = $this->order->$at_field;

        if ($this->date === null) {
            $this->date = Carbon::now();
            $this->updatedFields[$at_field] = $this->date;
        }

        return $this;
    }

    protected function setSubject()
    {
        $this->subject = [
            'deposit' => 'Anzahlungsrechnung',
            'interim' => 'Abschlussrechnung',
            'final' => 'Gesamtrechnung',
            'cancelled' => 'Stornorechnung',
        ][$this->type];
    }

    protected function setInvoiceId()
    {
        $id_field = $this->type . '_invoice_id';

        $this->invoiceId = $this->order->$id_field;

        if ($this->invoiceId === null) {
            $this->invoiceId = $this->order->venue->getNextInvoiceId();
            $this->updatedFields[$id_field] = $this->invoiceId;
        }

        return $this;
    }

    protected function setText()
    {
        $grace_days = 7;

        switch ($this->type) {
            case 'deposit':
                $this->text = [
                    'Bitte überweisen Sie den Betrag von ' . money($this->order->deposit_amount) . ' Euro bis zum ' .
                    $this->order->created_at->addDays($grace_days)->format('d.m.Y') .
                    ' unter Angabe der Rechnungsnummer auf das genannte Konto der Ostsächsische Sparkasse Dresden.'
                    ,
                    'BITTE BEACHTEN SIE: Der Geldeingang muss bis spätestens 7 Werktage nach Ihrer Reservierung erfolgt sein, spätere Eingänge werden nicht mehr berücksichtigt und die betreffende Bestellung wird automatisch storniert.'
                ];
                break;
            case 'interim':
                // TODO TODO copied from Pdf.php
                $grossTotal = collect($this->order->bookings)->sum('grossTotal');

                if ($this->order->deposit_paid_at) {
                    $grossTotal -= $this->order->deposit_amount;
                }

                $this->text = [
                    'Bitte überweisen Sie den Betrag von ' . money($grossTotal) . ' Euro bis zum ' .
                    ($this->order->interim_invoice_at ?: now())->addDays($grace_days)->format('d.m.Y') .
                    ' unter Angabe der Rechnungsnummer auf das genannte Konto der Ostsächsische Sparkasse Dresden.'
                    ,
                ];
                break;
            case 'final':
                // TODO TODO copied from Pdf.php
                $grossTotal = $this->order->grossTotal;

                if ($this->order->deposit_paid_at) {
                    $grossTotal -= $this->order->deposit_amount;
                }

                if ($this->order->interim_paid_at) {
                    $grossTotal -= $this->order->interim_amount;
                }

                $this->text = [
                    'Bitte überweisen Sie den Betrag von ' . money($grossTotal) . ' Euro bis zum ' .
                    ($this->order->final_invoice_at ?: now())->addDays($grace_days)->format('d.m.Y') .
                    ' unter Angabe der Rechnungsnummer auf das genannte Konto der Ostsächsische Sparkasse Dresden.'
                    ,
                ];

                break;
            case 'cancelled':
                $this->text = [
                    'R[ckerstattung YAY!!!',
                ];
                break;
        }

        if (
            $this->order->cash_payment &&
            ! in_array($this->order->type, ['deposit', 'cancelled']) &&
            (
                $this->type === 'final' && $this->order->final_paid_at
                ||
                $this->type === 'interim' && $this->order->interim_paid_at && $this->order->interim_is_final
            )
        ) {
            $this->text[] = 'Die Rechnung wurde am Veranstaltungstag in Bar beglichen!';
        }

        $this->text[] = 'Es gelten unser allgemeinen Geschäfts- und Vertragsbedingungen.';
    }
}
