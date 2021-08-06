@php
  // TODO: This belongs in the Controller!!!
  $total = 0;

  foreach ($order->bookings as $booking) {
    $booking_price = $booking->quantity * $booking->unit_price;
    $total += $booking_price;
  }

  $deposit = $total * $order->deposit / 100;
@endphp


<article
  x-data="{
    show: false
  }"
  class="sm:m-2 sm:p-2 lg:p-4 bg-white rounded-xl shadow text-sm sm:text-base"
>
  <div class="flex justify-between">
    <button
      @click="show = !show"
      class="hover:text-primary-dark rounded px-2 -mx-2 py-1"
    >
      {{ $order->customer->first_name . ' ' . $order->customer->last_name }}
    </button>
    <div>{{ $order->starts_at }}</div>
  </div>
  <div x-cloak class="bg-primary-light" x-show="show">
    @if ($order->customer->company)
      <div>Firma: {{ $order->customer->company }}</div>
    @endif
    <div>{{ $order->customer->street }} {{ $order->customer->street_no }}</div>
    <div>{{ $order->customer->zip }} {{ $order->customer->city }}</div>
    <div>Tel: {{ $order->customer->phone }}</div>
    <div>E-Mail:
      <x-link href="mailto:{{ $order->customer->email}}">{{ $order->customer->email }}</x-link>
    </div>
  </div>

    <livewire:bookings :bookings="$order->bookings" />
  </ul>
  <div class="flex justify-between">
    <div>
      <form
        wire:submit.prevent="save"
        action="#"
      >
        <select
          wire:model="selectedStatus"
          class="p-0"
          name="order-status"
          id="order-status"
        >
          <option value="deposit_mail_sent">Unbestätigt</option>
          <option value="deposit_paid">Bestätigt</option>
          <option value="intermed_paid">Anzahlungs-E-Mail versendet</option>
          <option value="intermed_mail_sent">Anzahlung eingegangen</option>
          <option value="final_mail_sent">Gesamtrechnungs-E-Mail versendet</option>
          <option value="final_paid">Gesamtrechnung bezahlt</option>
        </select>
        @if ($dirty)
          <button class="bg-green-300 px-2 py-1 rounded-xl">Save</button>
          <button wire:click.prevent="cancel" class="bg-green-300 px-2 py-1 rounded-xl">Cancel</button>
        @endif
      </form>
    </div>
    <div>
      <div>Deposit ({{ $order->deposit }}%): {{ number_format($deposit / 100, 2, ',', '.') }}</div>
      <div>Total: {{ number_format($total / 100, 2, ',', '.') }}</div>
    </div>
  </div>
</article>
