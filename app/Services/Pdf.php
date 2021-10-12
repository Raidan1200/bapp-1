<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;

class Pdf
{
    private $pdf;
    private $text;
    private $invoiceId;
    private $date;
    private $order;

    public function __construct($invoiceId, $date, $order)
    {
        $this->pdf = new Fpdi();
        $this->invoiceId = $invoiceId;
        $this->date = $date;
        $this->order = $order;

        $this->pdf->setTitle('TODO TITLE');
        $this->pdf->SetAutoPageBreak(true, 5);
        $this->pdf->AddPage();
        $this->pdf->setLeftMargin(25);

        // $this->pdf->image(__DIR__.'/../../images/'.$this->text->getText('rechnung', 'logo'), 141, 30, 39, 13);

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

        $blocks = $this->order->venue->invoice_blocks;

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
        $blocks = $this->order->venue->invoice_blocks;

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
        $this->pdf->setFont("Arial","", 7);
        $this->pdf->setY(46);
        $this->pdf->write(10, utf8_decode($this->order->venue->invoice_blocks['company']));
    }

    private function writeAnschrift()
    {
        $customer = $this->order->customer;

        $this->pdf->ln();
        $this->pdf->setFontSize(10);
        $this->pdf->write(5, utf8_decode($customer->company));
        $this->pdf->ln();
        $this->pdf->write(5, utf8_decode($customer->last_name).', '.utf8_decode($customer->first_name));
        $this->pdf->ln();
        $this->pdf->write(5, utf8_decode($customer->street).' '.utf8_decode($customer->street_no));
        $this->pdf->ln();
        $this->pdf->write(5, utf8_decode($customer->zip).', '.utf8_decode($customer->city));
    }

    private function writeBetreff() // TODO
    {
        $this->pdf->setFontSize(11);
        $this->pdf->ln(30);
        $this->pdf->write(5, 'TODO BETREFF');

        $this->pdf->setFontSize(9);
        $this->pdf->ln(7);

        $this->pdf->write(5, "Rechnungsnummer: ");
        $this->pdf->setX(57);
        $this->pdf->write(5, $this->invoiceId);
        $this->pdf->setX(100);
        $this->pdf->write(5, "Rechnungsdatum: ");
        $this->pdf->setX(129);
        $this->pdf->write(5, $this->date->format('d.m.Y'));
        $this->pdf->ln();
        $this->pdf->cell(170, 0, "", true);
    }

    private function drawPositionen() // TODO
    {
        $this->pdf->ln(8);
        $this->pdf->setFont("Arial","B", 8);
        $this->pdf->write(7, "Pos.");
        $this->pdf->setX(33);
        $this->pdf->write(7, "Artikel-Nr.");
        $this->pdf->setX(50);
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

        foreach ($this->order->bookings as $booking) {
            $this->pdf->ln();
            $this->pdf->write(6, $i++);
            $this->pdf->setX(33);
            $this->pdf->write(6, utf8_decode('ART NR?')); // TODO
            $this->pdf->setX(50);
            $this->pdf->write(6, utf8_decode($booking->package_name));
            $this->pdf->setX(96);
            $this->pdf->write(6, utf8_decode($booking->starts_at->format('d.m.Y H:i').'-'.$booking->ends_at->format('H:i')));
            $this->pdf->setX(128);
            $this->pdf->write(6, utf8_decode($booking->quantity));
            $this->pdf->setX(141);
            $this->pdf->write(6, utf8_decode(money($booking->unit_price).' Euro'));
            $this->pdf->setX(167);
            $this->pdf->write(6, utf8_decode(money($booking->grossTotal).' Euro'));
        }

        $this->pdf->ln();
        $this->pdf->write(6, $i);
        $this->pdf->setX(33);
        $this->pdf->write(6, utf8_decode('VERANZ'));
        $this->pdf->setX(50);
        $this->pdf->write(6, utf8_decode('Verrechnung Anzahlung TODO'));
        // if ($verrechnung < 0) {
            $this->pdf->setX(166);
        // } else {
        //     $this->pdf->setX(167);
        // }
        $this->pdf->write(6, utf8_decode('000' .' Euro'));

        $this->pdf->ln();
        $this->pdf->cell(170, 0, "", true);
        $this->pdf->ln();
        $this->pdf->setX(141);
        $this->pdf->write(5, "Gesamt netto");
        $this->pdf->setX(167);
        $this->pdf->write(5, money($this->order->netTotal).' Euro');

        foreach ($this->order->vats as $vat => $amount) {
            $this->pdf->ln();
            $this->pdf->setX(141);
            $this->pdf->write(5, $vat . ' MwSt.');
            $this->pdf->setX(167);
            $this->pdf->write(5, money($amount) . ' Euro');
        }

        $this->pdf->setFont("Arial","B", 8);
        $this->pdf->ln();
        $this->pdf->setX(141);
        $this->pdf->write(5, "Gesamt Brutto");
        $this->pdf->setX(167);
        $this->pdf->write(5, money($this->order->grossTotal).' Euro');

        $this->pdf->setFont("Arial","", 8);

        // $this->pdf->ln(20);
        // // Show proper payment text
        // if ($this->reservierung->getBarzahlung() == 1 && $this->prozent !== 20) {
        //     $this->pdf->ln();
        //     $this->pdf->write(5, utf8_decode($this->text->getText('rechnung', 'barzahlung')));
        // } else {
        //     $this->pdf->write(5, utf8_decode(sprintf($this->text->getText('rechnung', 'bezahlung'), number_format($this->betrag, 2, ',', '.'), $this->heute->modify('+'.$this->text->getText('rechnung', 'bezahlung_bis_tagen').'days')->format('d.m.Y'))));

        //     $this->pdf->ln();
        //     $this->pdf->write(5, utf8_decode($this->text->getText('rechnung', 'pay_info')));
        //     $this->pdf->ln();
        // }

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

    private function writeFuss() // TODO
    {
        $blocks = $this->order->venue->invoice_blocks;

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