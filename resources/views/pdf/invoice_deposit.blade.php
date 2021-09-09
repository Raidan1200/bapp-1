<x-pdf-layout>
  @include('pdf.header')

  <h1 class="text-2xl">Anzahlungsrechnung</h1>

  <div>
    <div>Rechnungsnummer: {{ $order->invoice_id }}</div>
    <div>Rechnungsdatum: {{ $date->format("d.m.Y") }}</div>
  </div>

  <h2 class="text-xl mt-6">Pakete</h2>
  @include ('pdf.bookings', ['bookings' => $order->bookings])

  @if ($order->items->count())
    <h2 class="text-xl mt-6">Produkte</h2>
    @include ('pdf.items', ['items' => $order->items])
  @endif

  @include ('pdf.totals', ['order' => $order])

  <p class="mt-8">
    Bitte überweisen Sie den Betrag von {{ money($order->gross_total) }} Euro bis
    {{ $order->created_at->addDays($venue->reminder_delay)->format('d.m.Y') }}
    unter Angabe der Rechnungsnummer ({{ $order->invoice_id }}) auf das unten genannte Konto
    bei {{ $venue->invoice_blocks['bank'] }}.
  </p>
  <p class="mt-2">
    <span class="font-semibold">BITTE BEACHTEN SIE:</span> Der Geldeingang muss bis spätestens 7 Werktage nach Ihrer Reservierung
    erfolgt sein. Spätere Eingänge werden nicht mehr berücksichtigt und die betreffende Bestellung
    wird automatisch storniert.
  </p>
  <p class="mt-4">
    Es gelten unser allgemeinen Geschäfts- und Vertragsbedingungen.
  </p>

  <div class="mt-8">
    Mit freundlichen Grüßen
  </div>

  <div class="mt-2 mb-12">
    <div>{{ $venue->invoice_blocks['manager'] }}</div>
    <div>Geschäftsführer</div>
    <div>{{ $venue->invoice_blocks['company'] }}</div>
  </div>

  @include('pdf.footer')
</x-pdf-layout>
