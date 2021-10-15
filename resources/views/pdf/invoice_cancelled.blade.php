<x-pdf-layout>
  @include('pdf.header')

  <h1 class="text-2xl">Anzahlungsrechnung</h1>

  <div>
    <div>Rechnungsnummer: {{ $order->cancelled_invoice_id }}</div>
    <div>Rechnungsdatum: {{ $order->cancelled_at->timezone('Europe/Berlin')->format("d.m.Y") }}</div>
  </div>

  <h2 class="text-xl mt-6">Pakete</h2>
  @include ('pdf.bookings', ['bookings' => $order->bookings])

  @if ($order->items->count())
    <h2 class="text-xl mt-6">Produkte</h2>
    @include ('pdf.items', ['items' => $order->items])
  @endif

  @include ('pdf.totals', ['order' => $order])

  @include('pdf.footer')
</x-pdf-layout>
