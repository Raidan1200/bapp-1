@props(['order'])
@php
  $total = 0;

  foreach ($order->bookings as $booking) {
    $booking_price = $booking->quantity * $booking->product->unit_price;
    $total += $booking_price;
  }

  $deposit = $total * $order->deposit / 100;
@endphp

<li class="m-2 p-2 sm:p-4 bg-white rounded-xl shadow">
  <article x-data="{
    show: false
  }">
    <button class="hover:text-primary-dark rounded px-2 -mx-2 py-1" @click="show = !show">
      {{ $order->customer->first_name . ' ' . $order->customer->last_name }}
    </button>
    <div class="bg-primary-light" x-show="show">
      @if ($order->customer->company)
        <div>Company: {{ $order->customer->company }}</div>
      @endif
      <div>{{ $order->customer->street }} {{ $order->customer->street_no }}</div>
      <div>{{ $order->customer->zip }} {{ $order->customer->city }}</div>
      <div>Phone: {{ $order->customer->phone }}</div>
      <div>E-Mail: {{ $order->customer->email }}</div>
    </div>
    <ul class="py-2">
      @foreach ($order->bookings as $booking)
        <li>
          {{ $booking->product->name }}
          @if ($booking->flat)
            <span>(Flat)</span>
          @endif
          - ({{ $booking->quantity }} * {{ number_format($booking->product->unit_price / 100, 2, ',', '.') }}€)
          - ({{ $booking->vat }}% MwSt)
          - {{ number_format($booking->quantity * $booking->product->unit_price / 100, 2, ',', '.') }}€
        </li>
      @endforeach
    </ul>
    <div class="flex justify-between">
      <div>
        <form action="#">
          <select class="p-0" name="" id="">
            <option value="unconfirmed">Unbestätigt</option>
            <option value="confirmed">Bestätigt</option>
            <option value="deposit-email-sent">Anzahlungs-E-Mail versendet</option>
            <option value="deposit-paid">Anzahlung eingegangen</option>
            <option value="final-invoice-email-sent">Gesamtrechnungs-E-Mail versendet</option>
            <option value="final-invoice-paid">Gesamtrechnung bezahlt</option>
          </select>
        </form>
      </div>
      <div>
        <div>Deposit ({{ $order->deposit }}%): {{ number_format($deposit / 100, 2, ',', '.') }}</div>
        <div>Total: {{ number_format($total / 100, 2, ',', '.') }}</div>
      </div>
    </div>
  </article>
</li>
