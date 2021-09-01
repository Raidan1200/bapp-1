<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice {{ $order->invoice_id }}</title>
</head>
<body>

  <div>Date (immutable): {{ $date }}</div>
  <div>Venue: {{ $venue->name }}</div>
  <div>Customer: {{ $customer->name }}</div>

  <h2>Pakete</h2>
  <ul>
    @foreach ($order->bookings as $booking)
      <li>{{ $booking->package_name }} : {{ $booking->quantity }} * {{ $booking->unit_price }} = {{ $booking->grossTotal }}</li>
    @endforeach
  </ul>

  <h2>Produkte</h2>
  <ul>
    @foreach ($order->items as $item)
      <li>{{ $item->product_name }} : {{ $item->quantity }} * {{ $item->unit_price }} = {{ $item->grossTotal }}</li>
    @endforeach
  </ul>

  <div>Gesamt Netto: {{ $data['net_total'] }}</div>

  <ul>
    @foreach ($data['vat'] as $vat => $amount)
      <li>zzgl. {{ $vat }}% MwSt: {{ $amount }}</li>
    @endforeach
  </ul>

  <div>Gesamt Brutto: {{ $data['gross_total'] }}</div>
  <div>Gesamt Brutto (Model): {{ $data['gross_total2'] }}</div>

</body>
</html>
