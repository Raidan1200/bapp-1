<x-email-layout>

  <h1>Stornorechnung</h1>

  Hallo {{ $order->customer->first_name }},

  <p>Sie haben leider Ihre Bestellung bei {{ $order->venue->name }} storniert.</p>

  <p>Wir bedauern dies sehr und werden Ihnen innerhalb der nächsten 14 Tage ihr Geld zurück überweisen.</p>

</x-email-layout>
