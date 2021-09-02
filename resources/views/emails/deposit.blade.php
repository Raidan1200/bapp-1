<h1>Anzahlungsrechnung</h1>
<pre>
Hallo {{ $order->customer->first_name }},

wir haben Deine Buchungsanfrage erhalten.

Sie haben für {{ $order->starts_at }} angefragt:

@foreach ($order->bookings as $booking)
- {{ $booking->quantity }} Mal {{ $booking->package_name }}
@endforeach

Bitte überweisen Sie uns all ihr Geld.
Alternativ genügt auch eine Anzahlung von {{ number_format($order->deposit / 100, 2, ',', '.') }}€.

Bankdaten Trallalla.
</pre>
