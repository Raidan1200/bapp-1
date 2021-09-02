<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Anzahlungsrechnung {{ $order->invoice_id }}</title>
</head>
<body>

  <h1>Anzahlungsrechnung</h1>

  <div>Date (immutable): {{ $date }}</div>
  <div>Venue: {{ $venue->name }}</div>

  <div>
      <div>{{ $venue->invoice_blocks['header']['left'][0] }}</div>
  </div>

  <div>Customer: {{ $customer->name }}</div>

  <h2>Pakete</h2>
  <ul>
    @foreach ($order->bookings as $booking)
      <li>{{ $booking->package_name }} : {{ $booking->quantity }} * {{ $booking->unit_price }} = {{ $booking->grossTotal }}</li>
    @endforeach
  </ul>

  @if ($order->items->count())
    <h2>Produkte</h2>
    <ul>
      @foreach ($order->items as $item)
        <li>{{ $item->product_name }} : {{ $item->quantity }} * {{ $item->unit_price }} = {{ $item->grossTotal }}</li>
      @endforeach
    </ul>
  @endif


  <div>Gesamt Netto: {{ $order->net_total }}</div>

  <ul>
    @foreach ($order->vats as $vat => $amount)
      <li>zzgl. {{ $vat }}% MwSt: {{ $amount }}</li>
    @endforeach
  </ul>

  <div>Gesamt Brutto: {{ $order->gross_total }}</div>
</body>
</html>
