@props(['order'])
@php
  // TODO: This belongs in the Controller!!!
  $total = 0;

  foreach ($order->bookings as $booking) {
    $booking_price = $booking->quantity * $booking->unit_price;
    $total += $booking_price;
  }

  $deposit = $total * $order->deposit / 100;
@endphp

<li class="m-2 p-2 sm:p-4 bg-white rounded-xl shadow">
  <article x-data="{
    show: false
  }">
    <div class="flex justify-between">
      <button class="hover:text-primary-dark rounded px-2 -mx-2 py-1" @click="show = !show">
        {{ $order->customer->first_name . ' ' . $order->customer->last_name }}
      </button>
      <div>{{ $order->starts_at }}</div>
    </div>
    <div x-cloak class="bg-primary-light" x-show="show">
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
    </ul>
    <div class="flex justify-between">
      <div>
        <livewire:order :order="$order" />
      </div>
      <div>
        <div>Deposit ({{ $order->deposit }}%): {{ number_format($deposit / 100, 2, ',', '.') }}</div>
        <div>Total: {{ number_format($total / 100, 2, ',', '.') }}</div>
      </div>
    </div>
  </article>
</li>
