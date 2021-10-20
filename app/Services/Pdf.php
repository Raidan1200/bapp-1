<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;

class Pdf
{
    private $pdf;
    private $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->pdf = new Fpdi();
        $this->invoice = $invoice;

        $this->pdf->setTitle($this->invoice->type . ' ' . $this->invoice->invoiceId);
        $this->pdf->SetAutoPageBreak(true, 5);
        $this->pdf->AddPage();
        $this->pdf->setLeftMargin(25);

        $path = $this->invoice->order->venue->slug . '/logo.png';
        $this->pdf->image(Storage::disk('public')->path($path), 141, 30, 39, 13);

        $this->pdf->setTextColor(0,0,0);

        $this->drawKnicklinien();
        $this->writeKontakt(140, 48);
        $this->writeBank(140, 70);
        $this->writeKopf();
        $this->writeAnschrift();
        $this->writeBetreff();
        $this->drawPositionen();
        $this->writeFuss();
    }

    public function output($type = 'I', $dest = '')
    {
        return $this->pdf->output($type, $dest);
    }

    private function drawKnicklinien()
    {
        $this->pdf->setDrawColor(128, 128, 128);
        $this->pdf->setLineWidth(0.01);
        $this->pdf->line(0, 105, 5, 105);
        $this->pdf->line(0, 148.5, 5, 148.5);
        $this->pdf->setLineWidth(0.1);
    }

    private function writeKontakt($x, $y)
    {
        $this->pdf->setFont("Arial","B", 7);
        $this->pdf->setXY($x, $y);
        $this->pdf->write(5, "Telefon:");
        $this->pdf->setXY($x, $y+4);
        $this->pdf->write(5, "Fax:");
        $this->pdf->setXY($x, $y+11);
        $this->pdf->write(5, "E-Mail:");
        $this->pdf->setXY($x, $y+15);
        $this->pdf->write(5, "Internet:");

        $blocks = $this->invoice->order->venue->invoice_blocks;

        $this->pdf->setFont("Arial","", 7);
        $this->pdf->setXY($x+15, $y);
        $this->pdf->write(5, utf8_decode($blocks['phone']));
        $this->pdf->setXY($x+15, $y+4);
        $this->pdf->write(5, utf8_decode($blocks['fax']));
        $this->pdf->setXY($x+15, $y+11);
        $this->pdf->write(5, utf8_decode($blocks['email']));
        $this->pdf->setXY($x+15, $y+15);
        $this->pdf->write(5, utf8_decode($blocks['web']));
    }

    private function writeBank($x, $y)
    {
        $blocks = $this->invoice->order->venue->invoice_blocks;

        $this->pdf->setFont("Arial","", 7);
        $this->pdf->setXY($x, $y);
        $this->pdf->write(5, utf8_decode($blocks['bank']));

        $this->pdf->setXY($x+15, $y+4);
        $this->pdf->write(5, utf8_decode($blocks['iban']));
        $this->pdf->setXY($x+15, $y+8);
        $this->pdf->write(5, utf8_decode($blocks['bic']));

        $this->pdf->setFont("Arial","B", 7);
        $this->pdf->setXY($x, $y+4);
        $this->pdf->write(5, "IBAN:");
        $this->pdf->setXY($x, $y+8);
        $this->pdf->write(5, "BIC:");
    }

    private function writeKopf()
    {
        $blocks = $this->invoice->order->venue->invoice_blocks;
        $this->pdf->setFont("Arial","", 7);
        $this->pdf->setY(46);
        $this->pdf->write(10, utf8_decode(
            $blocks['company'] . ' * ' .
            $blocks['street'] .
            $blocks['street_no'] . ' * ' .
            $blocks['zip'] .
            $blocks['city']
        ));
    }

    private function writeAnschrift()
    {
        $customer = $this->invoice->order->customer;

        $this->pdf->ln();
        $this->pdf->setFontSize(10);
        $this->pdf->write(5, utf8_decode($customer->company));
        $this->pdf->ln();
        $this->pdf->write(5, utf8_decode($customer->lasfirst_namet_name).' '.utf8_decode($customer->last_name));
        $this->pdf->ln();
        $this->pdf->write(5, utf8_decode($customer->street).' '.utf8_decode($customer->street_no));
        $this->pdf->ln();
        $this->pdf->write(5, utf8_decode($customer->zip).' '.utf8_decode($customer->city));
    }

    private function writeBetreff()
    {
        $this->pdf->setFontSize(11);
        $this->pdf->ln(30);
        $this->pdf->write(5, $this->invoice->subject);

        $this->pdf->setFontSize(9);
        $this->pdf->ln(7);

        $this->pdf->write(5, "Rechnungsnummer: ");
        $this->pdf->setX(57);
        $this->pdf->write(5, $this->invoice->invoiceId);
        $this->pdf->setX(100);
        $this->pdf->write(5, "Rechnungsdatum: ");
        $this->pdf->setX(129);
        $this->pdf->write(5, $this->invoice->date->format('d.m.Y'));
        $this->pdf->ln();
        $this->pdf->cell(170, 0, "", true);
    }

    private function drawPositionen()
    {
        $order = $this->invoice->order;

        $this->pdf->ln(8);
        $this->pdf->setFont("Arial","B", 8);
        $this->pdf->write(7, "Pos.");
        $this->pdf->setX(33);
        // $this->pdf->write(7, "Artikel-Nr.");
        // $this->pdf->setX(50);
        $this->pdf->write(7, "Bezeichnung");
        $this->pdf->setX(96);
        $this->pdf->write(7, "Leistungsdatum");
        $this->pdf->setX(128);
        $this->pdf->write(7, "Anzahl");
        $this->pdf->setX(141);
        $this->pdf->write(7, "Einzelpreis brutto");
        $this->pdf->setX(167);
        $this->pdf->write(7, "Gesamtpreis brutto");
        $this->pdf->ln();
        $this->pdf->cell(170, 0, "", true);

        $this->pdf->setFont("Arial","", 7);
        $i = 1;
        $gesamt = 0;

        foreach ($order->bookings as $booking) {
            $this->pdf->ln();
            $this->pdf->write(6, $i++);
            $this->pdf->setX(33);
            // $this->pdf->write(6, utf8_decode('ART NR?')); // LATER
            // $this->pdf->setX(50);
            $package_name = $booking->package_name;
            if ($this->invoice->type === 'deposit') {
                $package_name .= " ({$booking->deposit}%)";
            }

            $this->pdf->write(6, utf8_decode($package_name));
            $this->pdf->setX(96);
            $this->pdf->write(6, utf8_decode($booking->starts_at->format('d.m.Y H:i').'-'.$booking->ends_at->format('H:i')));
            $this->pdf->setX(128);
            $this->pdf->write(6, utf8_decode($booking->quantity));
            $this->pdf->setX(141);

            $unit_price = ($this->invoice->type === 'deposit')
                ? $booking->grossDeposit
                : $booking->unit_price;

            $this->pdf->write(6, utf8_decode(money($unit_price).' Euro'));
            $this->pdf->setX(167);

            $gross_total = ($this->invoice->type === 'deposit')
                ? $booking->grossDepositTotal
                : $booking->grossTotal;

            $this->pdf->write(6, utf8_decode(money($gross_total).' Euro'));
        }

        if ($this->invoice->type === 'final') {
            foreach ($order->items as $item) {
                $this->pdf->ln();
                $this->pdf->write(6, $i++);
                $this->pdf->setX(33);
                // $this->pdf->write(6, utf8_decode('ART NR?')); // LATER
                // $this->pdf->setX(50);
                $this->pdf->write(6, utf8_decode($item->product_name));
                $this->pdf->setX(96);
                $this->pdf->write(6, '');
                $this->pdf->setX(128);
                $this->pdf->write(6, utf8_decode($item->quantity));
                $this->pdf->setX(141);
                $this->pdf->write(6, utf8_decode(money($item->unit_price).' Euro'));
                $this->pdf->setX(167);
                $this->pdf->write(6, utf8_decode(money($item->grossTotal).' Euro'));
            }
        }

        if (in_array($this->invoice->type, ['interim', 'final']) && $order->deposit_paid_at) {
            $this->pdf->ln();
            $this->pdf->write(6, $i++);
            $this->pdf->setX(33);
            // $this->pdf->write(6, utf8_decode('VERANZ'));
            // $this->pdf->setX(50);
            $this->pdf->write(6, utf8_decode('Verrechnung Anzahlung ('.$order->deposit_invoice_id.')'));
            $this->pdf->setX(167);
            $this->pdf->write(6, utf8_decode(money($order->deposit_amount * -1) .' Euro'));
        }

        if ($this->invoice->type === 'final' && $order->interim_paid_at) {
            $this->pdf->ln();
            $this->pdf->write(6, $i++);
            $this->pdf->setX(33);
            // $this->pdf->write(6, utf8_decode('VERANZ'));
            // $this->pdf->setX(50);
            $this->pdf->write(6, utf8_decode('Verrechnung Abschlussrechnung ('.$order->interim_invoice_id.')'));
            $this->pdf->setX(167);
            $this->pdf->write(6, utf8_decode(money($order->interim_amount * -1) .' Euro'));
        }

        $this->pdf->ln();
        $this->pdf->cell(170, 0, "", true);
        $this->pdf->ln();
        $this->pdf->setX(141);
        $this->pdf->write(5, "Gesamt netto");
        $this->pdf->setX(167);

        if ($this->invoice->type === 'deposit') {
            $netTotal = $order->netDepositTotal;
        } elseif ($this->invoice->type === 'interim') {
            $netTotal = collect($order->bookings)->sum('netTotal');
        } elseif ($this->invoice->type === 'final') {
            $netTotal = $order->netTotal;
        } elseif ($this->invoice->type === 'cancelled') {
            $netTotal = 0;
        }

        $this->pdf->write(5, money($netTotal).' Euro');

        $vats = ($this->invoice->type === 'deposit')
            ? $order->depositVats
            : $order->vats;

        foreach ($vats as $vat => $amount) {
            $this->pdf->ln();
            $this->pdf->setX(141);
            $this->pdf->write(5, $vat . '% MwSt.');
            $this->pdf->setX(167);
            $this->pdf->write(5, money($amount) . ' Euro');
        }

        $this->pdf->setFont("Arial","B", 8);
        $this->pdf->ln();
        $this->pdf->setX(141);
        $this->pdf->write(5, "Gesamt Brutto");
        $this->pdf->setX(167);


        if ($this->invoice->type === 'deposit') {
            $grossTotal = $order->deposit_amount;

        } elseif ($this->invoice->type === 'interim') {
            $grossTotal = collect($order->bookings)->sum('grossTotal');

            if ($order->deposit_paid_at) {
                $grossTotal -= $order->deposit_amount;
            }

        } elseif ($this->invoice->type === 'final') {
            $grossTotal = $order->grossTotal;

            if ($order->deposit_paid_at) {
                $grossTotal -= $order->deposit_amount;
            }

            if ($order->interim_paid_at) {
                $grossTotal -= $order->interim_amount;
            }

        } elseif ($this->invoice->type === 'cancelled') {
            //
        }

        $this->pdf->write(5, money($grossTotal).' Euro');

        $this->pdf->setFont("Arial","", 9);
        $this->pdf->ln(20);

        foreach ($this->invoice->text as $text) {
            $this->pdf->write(5, utf8_decode($text));
            $this->pdf->ln(8);
        }
        // TODO
        // $this->pdf->write(5, utf8_decode($this->invoice->text->getText('rechnung', 'pay_info')));
        // $this->pdf->ln();

        // $this->pdf->ln();
        // $this->pdf->write(5, utf8_decode($this->text->getText('rechnung', 'agb')));
        // $this->pdf->ln();
        // $this->pdf->ln();
        // $this->pdf->ln();
        // $this->pdf->write(5, utf8_decode($this->text->getText('rechnung', 'mfg')));
        // $this->pdf->ln();
        // $this->pdf->ln();
        // $this->pdf->write(5, utf8_decode($this->text->getText('rechnung', 'mfg_name')));
        // $this->pdf->ln();
        // $this->pdf->write(5, utf8_decode($this->text->getText('rechnung', 'mfg_bezeichnung')));
        // $this->pdf->ln();
        // $this->pdf->write(5, utf8_decode($this->text->getText('rechnung', 'mfg_firma')));

    }

    private function writeFuss()
    {
        $blocks = $this->invoice->order->venue->invoice_blocks;

        $this->pdf->setFont("Arial","", 8);
        $this->pdf->setY(260);
        $this->pdf->cell(170, 0, "", true);
        $this->pdf->ln(2);
        $this->pdf->write(5, utf8_decode($blocks['company']));
        $this->pdf->setX(95);
        $this->pdf->write(5, utf8_decode('SteuerNr.: '.$blocks['tax_id']));
        $this->pdf->setX(150);
        $this->pdf->write(5, utf8_decode('Firmensitz '.$blocks['city']));
        $this->pdf->ln();
        $this->pdf->write(5, utf8_decode('Geschäftsführer'));
        $this->pdf->setX(95);
        $this->pdf->write(5, utf8_decode('HRB '.$blocks['hrb']));
        $this->pdf->setX(150);
        $this->pdf->write(5, utf8_decode($blocks['street'].' '.$blocks['street_no']));
        $this->pdf->ln();
        $this->pdf->write(5, utf8_decode($blocks['manager']));
        $this->pdf->setX(95);
        $this->pdf->write(5, utf8_decode($blocks['court']));
        $this->pdf->setX(150);
        $this->pdf->write(5, utf8_decode($blocks['zip'].' '.$blocks['city']));
        // $this->pdf->ln();
        // $this->pdf->write(5, utf8_decode($blocks['company']));
        // $this->pdf->setX(95);
        // $this->pdf->write(5, utf8_decode($blocks['company']));
        // $this->pdf->setX(150);
        // $this->pdf->write(5, utf8_decode($blocks['company']));
        // $this->pdf->ln();
        // $this->pdf->write(5, utf8_decode($blocks['company']));
        // $this->pdf->setX(95);
        // $this->pdf->write(5, utf8_decode($blocks['company']));
        // $this->pdf->setX(150);
        // $this->pdf->write(5, utf8_decode($blocks['company']));
    }
}