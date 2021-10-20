<h1>Abschlussrechnung</h1>
<pre>
Hallo {{ $order->customer->first_name }},

Hier ist ihre Abschlussrechnung.

@foreach ($order->bookings as $booking)
- {{ $booking->quantity }} Mal {{ $booking->package_name }}
@endforeach

Bankdaten Trallalla.
</pre>
