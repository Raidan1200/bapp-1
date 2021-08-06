<table class="table-fixed w-full">
  <thead>
    <tr class="bg-gray-100">
      <td class="w-5/12 border-r px-1 border-white">Produkt</td>
      <td class="w-1/12 text-center px-1 border-r border-white">Flat</td>
      <td class="w-1/12 text-right px-1 border-r border-white">#</td>
      <td class="w-1/12 text-right px-1 border-r border-white">Preis</td>
      <td class="w-1/12 text-right px-1 border-r border-white">MwSt</td>
      <td class="w-1/12 text-right px-1 border-r border-white">Anz.</td>
      <td class="w-2/12 text-right px-1">Gesamt</td>
    </tr>
  </thead>
  @foreach ($bookings as $booking)
    <tr>
      <td>
        {{ $booking->product_name }}
      </td>
      <td class="text-center">
        @if ($booking->is_flat)
          <x-icons.check />
        @endif
      </td>
      <td class="text-right">
        {{ $booking->quantity }}
      </td>
      <td class="text-right">
        {{ number_format($booking->unit_price / 100, 2, ',', '.') }}
      </td>
      <td class="text-right">
        {{ $booking->vat }}%
      </td>
      <td class="text-right">
        {{ $booking->deposit }}%
      </td>
      <td class="text-right">
        {{ number_format($booking->quantity * $booking->unit_price / 100, 2, ',', '.') }}
      </td>
    </tr>
  @endforeach
</table>
