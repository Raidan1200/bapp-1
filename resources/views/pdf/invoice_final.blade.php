<x-pdf-layout>
  @include('pdf.header')

  <h1 class="text-2xl">Abschlussrechnung</h1>

  <div>
    <div>Rechnungsnummer: {{ $order->final_invoice_id }}</div>
    <div>Rechnungsdatum: {{ $order->final_invoice_at->timezone('Europe/Berlin')->format("d.m.Y") }}</div>
  </div>

  <h2 class="text-xl mt-6">Pakete</h2>
  @include ('pdf.bookings', ['bookings' => $order->bookings])

  @if ($order->items->count())
    <h2 class="text-xl mt-6">Produkte</h2>
    @include ('pdf.items', ['items' => $order->items])
  @endif

  @include ('pdf.totals', ['order' => $order])

  Bereits bezahlt: {{ money($order->deposit_amount + $order->interim_amount) }}

  <p class="mt-8">
    Bitte überweisen Sie den Betrag von
    {{-- TODO: THIS SHOULD NOT BE IN THE VIEW, BUT IN THE INVOICE SERVICE??? --}}
    {{ money($order->interim_is_final
        ? $order->grossTotal - $order->deposit_amount
        : $order->grossTotal - $order->deposit_amount - $order->interim_amount
      )
    }} Euro
    unter Angabe der Rechnungsnummer ({{ $order->invoice_id }}) auf das unten genannte Konto
    bei {{ $venue->invoice_blocks['bank'] }}.
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
