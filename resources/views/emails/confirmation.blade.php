<h1>Buchungsbestätigung</h1>
<pre>
Hallo {{ $order->customer->first_name }},

Hiermit bestätigen wir Ihre Buchungsanfrage vom {{ $order->created_at }}.

Bla bla {{ $order->starts_at }} ... bla.

</pre>
