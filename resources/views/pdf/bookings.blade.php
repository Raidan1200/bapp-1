<table class="w-full">
  <thead class="font-semibold">
    <tr>
      <td>Pos.</td>
      <td>Bezeichnung</td>
      <td>Leistungsdatum</td>
      <td class="text-center">Anzahl</td>
      <td class="text-right">Einzelpreis brutto</td>
      <td class="text-right">Gesamtpreis brutto</td>
    </tr>
  </thead>
  <tbody>
    @foreach ($bookings as $booking)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $booking->package_name }}</td>
        <td>{{ $booking->starts_at->format('d.m.Y. H:i') }} - {{ $booking->ends_at->format('H:i') }}</td>
        <td class="text-center">{{ $booking->quantity }}</td>
        <td class="text-right">{{ money($booking->unit_price) }} Euro</td>
        <td class="text-right">{{ money($booking->gross_total) }} Euro</td>
      </tr>
    @endforeach
  </tbody>
</table>
