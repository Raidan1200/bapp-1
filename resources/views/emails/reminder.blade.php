<pre>
Hallo {{ $order->customer->first_name }},

Sie haben Ihre Bestellung vom {{ $order->created_at }} immer noch nicht bezahlt. Was ist da los???!!!

Wenn Sie nicht binnen {{ $order->venue->check_delay + 1 }} (fake) Tagen bezahlen, wird die Bestellung automatisch aus dem System gelöscht.
</pre>
