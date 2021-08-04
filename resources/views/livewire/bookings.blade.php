@foreach ($bookings as $booking)
  <li>
    <table class="w-full">
      <tr>
        <td class="w-2/5">
          {{ $booking->product_name }}
          @if ($booking->flat)
            <span>(Flat)</span>
          @endif
        </td>
        <td class="w-1/5">
          {{ $booking->quantity }} * {{ number_format($booking->unit_price / 100, 2, ',', '.') }}€
        </td>
        <td class="w-1/5">
          {{ $booking->vat }}% MwSt
        </td>
        <td class="w-1/5">
          {{ number_format($booking->quantity * $booking->unit_price / 100, 2, ',', '.') }}€
        </td>
      </tr>
    </table>
  </li>
@endforeach
