@php
  // TODO: This belongs in the Controller!!!
@endphp

<article
  x-data="{
    showCustomerData: false
  }"
  class="sm:m-2 sm:p-2 lg:p-4 bg-white rounded-xl shadow text-sm sm:text-base"
>
  {{-- Headline --}}
  <div class="flex justify-between">
    <button
      @click="showCustomerData = !showCustomerData"
      class="hover:text-primary-dark rounded px-2 -mx-2 py-1 font-semibold"
    >
      {{ $order->customer->first_name . ' ' . $order->customer->last_name }}
    </button>
    <div class="font-semibold">{{ $order->starts_at->formatLocalized('%a %d.%m %H:%M') }}</div>
  </div>
  {{-- Customer Data --}}
  <div x-cloak class="bg-primary-light" x-show="showCustomerData">
    <livewire:customer :customer="$order->customer" />
  </div>
  <div class="mt-2">
    <livewire:bookings :bookings="$order->bookings->toArray()" :orderId="$order->id" />
  </div>
  <div class="flex justify-between mt-2">
    <div>
      <form
        wire:submit.prevent="save"
        action="#"
      >
        <div>
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
            <button
              wire:click.prevent="cancel"
              class="bg-green-300 px-2 py-1 rounded-xl"
            >Cancel</button>
          @endif
        </div>
      </form>
      {{-- @can('delete orders')
        <div class="text-right m-4">
          @csrf
          @method('delete')
          <x-button
            type="button"
            class="bg-red-300 hover:bg-red-600"
          >
            <div
              x-data
              @click.prevent="$dispatch('open-delete-modal', {
                route: '{{ route('dashboard', $order->id) }}',
                entity: 'die Bestellung von {{ $order->customer->first_name.' '.$order->customer->last_name }}',
                subText: '',
              })"
            >
              Löschen (TODO)
            </div>
          </x-button>
        </div>
      @endcan --}}
    </div>
    <div>
      <div>Anzahlung: {{ number_format($this->deposit / 100, 2, ',', '.') }}</div>
      <div>Gesamt: {{ number_format($this->total / 100, 2, ',', '.') }}</div>
    </div>
  </div>
</article>
