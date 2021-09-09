<table class="w-full">
  <thead class="font-semibold">
    <tr>
      <td>Pos.</td>
      <td>Bezeichnung</td>
      <td class="text-center">Anzahl</td>
      <td class="text-right">Einzelpreis brutto</td>
      <td class="text-right">Gesamtpreis brutto</td>
    </tr>
  </thead>
  <tbody>
    @foreach ($items as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->product_name }}</td>
        <td class="text-center">{{ $item->quantity }}</td>
        <td class="text-right">{{ money($item->unit_price) }} Euro</td>
        <td class="text-right">{{ money($item->gross_total) }} Euro</td>
      </tr>
    @endforeach
  </tbody>
</table>
