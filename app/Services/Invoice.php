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

    public function makePdf()
    {
        // Dynamic Data:
        //   Betreff: Anzahlungsrechnung
        //   Text: Bitte überweisen Sie
        //     mit 2 Platzhaltern für Betrag und Datum

        // TODO: put subject line

        return response()->streamDownload(fn() =>
            (new Pdf($this))->output()
        );
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
        switch ($this->type) {
            case 'deposit':
                $this->subject = 'Anzahlungsrechnung';
                break;
            case 'interim':
                $this->subject = 'Abschlussrechnung';
                break;
            case 'final':
                $this->subject = 'Gesamtrechnung';
                break;
            case 'cancelled':
                $this->subject = 'Stornorechnung';
                break;
            default:
                $this->subject = 'Rechnung';
        }
    }

    // BITTE BEACHTEN SIE: Der Geldeingang muss bis spätestens 7 Werktage nach Ihrer Reservierung erfolgt sein, spätere Eingänge
    // werden nicht mehr berücksichtigt und die betreffende Bestellung wird automatisch storniert.
    // Es gelten unser allgemeinen Geschäfts- und Vertragsbedingungen.

    protected function setText()
    {
        $grace_days = 7;

        switch ($this->type) {
            case 'deposit':
                $this->text[] =
                    'Bitte überweisen Sie den Betrag von ' .
                    money($this->order->deposit_amount) .
                    ' Euro bis ' .
                    $this->order->created_at->addDays($grace_days)->format('Y M.D.') .
                    ' unter Angabe der Rechnungsnummer auf das genannte Konto der Ostsächsische Sparkasse Dresden.';
                break;
            case 'interim':
                $this->text = 'Abschlussrechnung';
                break;
            case 'final':
                $this->text = 'Gesamtrechnung';
                break;
            case 'cancelled':
                $this->text = 'Stornorechnung';
                break;
            default:
                $this->text = 'Rechnung';
        }
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
}
