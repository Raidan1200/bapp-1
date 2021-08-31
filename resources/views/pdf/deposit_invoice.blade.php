<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice {{ $order->invoice_id }}</title>
</head>
<body>
<pre>
  Date (immutable): {{ $date }}
  Venue: {{ $venue->name }}
  Customer: {{ $customer->name }}
  Total: {{ $data['total'] }}

  @foreach ($data['items'] as $item)
    <div>{{ "{$item['package_name']} : {$item['quantity']} * {$item['unit_price']}" }}</div>
  @endforeach
</pre>
</body>
</html>
