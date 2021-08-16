<article
  x-data="{
    editCustomer: false,
    editNote: @entangle('editingNote'),
  }"
  class="sm:m-2 sm:p-2 lg:p-4 bg-white rounded-xl shadow text-sm sm:text-base border-t
  {{ ($order->status === 'fresh' || $order->status === 'cancelled') ? 'border-red-500' : '' }}
  {{ ($order->status === 'deposit_paid') ? 'border-yellow-500' : '' }}
  {{ ($order->status === 'interim_paid') ? 'border-blue-500' : '' }}
  {{ ($order->status === 'final_paid') ? 'border-green-500' : '' }} "
>
  {{--
    TODO TODO: I dont get it. This does not work.
               Also, how do I prevent n+1 problem?
    Zuletzt bearbeitet von {{ $order->latestAction->id }}
  --}}
  {{-- Headline --}}
  <div class="flex justify-between">
    <button
      @click="editCustomer = !editCustomer"
      class="hover:text-primary-dark rounded px-2 -mx-2 py-1 font-semibold"
    >
      {{ $order->customer->first_name . ' ' . $order->customer->last_name }}
    </button>
    <div class="font-semibold">{{ $order->starts_at->timezone('Europe/Berlin')->formatLocalized('%a %d.%m %H:%M') }}</div>
  </div>
  {{-- Customer Data --}}
  <div x-cloak class="bg-primary-light" x-show="editCustomer">
    <livewire:customer :customer="$order->customer" />
  </div>
  <div class="mt-2">
    <livewire:bookings :bookings="$order->bookings->toArray()" :orderId="$order->id" />
  </div>
  <form
    wire:submit.prevent="save"
    action="#"
  >
    <div class="flex justify-between mt-2">
      {{-- Note --}}
      <div
        @click="editNote = true"
        class="flex-1 mr-4"
      >
        <textarea
          x-show="editNote"
          wire:model.defer="notes"
          class="w-full"
          name="order-notes"
          id="order-notes"
        >{{ $notes }}</textarea>
        <div
          x-show="!editNote"
        >
          @if ($order->notes)
            {!! nl2br(e($notes)) !!}
          @else
            <span class="text-gray-400">Keine Anmerkungen</span>
          @endif
        </div>
      </div>
      <div>
        <div>
          <select
            wire:model="selectedStatus"
            class="py-0"
            name="order-status"
            id="order-status"
          >
            <option value="fresh">Nicht bestätigt</option>
            <option value="deposit_paid">Anzahlung eingegangen</option>
            <option value="interim_paid">Zwischenrechnung bezahlt</option>
            <option value="final_paid">Schlussrechnung bezahlt</option>
            <option value="cancelled">Storniert</option>
          </select>
        </div>
        <div>
          <div>Anzahlung: {{ number_format($this->deposit / 100, 2, ',', '.') }}</div>
          <div>Gesamt: {{ number_format($this->total / 100, 2, ',', '.') }}</div>
        </div>
      </div>
    </div>
    @if ($dirty)
      <div class="text-right">
        @can('delete orders')
          <div class="sm:text-right mt-8 inline-block">
            <x-button
              type="button"
              class="hover:bg-red-500"
            >
              <div
                x-data
                @click.prevent="$dispatch('open-delete-modal', {
                  route: '{{ route('orders.destroy', $order) }}',
                  entity: '{{ $order->customer->name }}',
                  subText: '',
                })"
              >
                Bestellung löschen
              </div>
            </x-button>
          </div>
        @endcan
        <button class="bg-green-300 px-2 py-1 rounded-xl">Save</button>
        <button
          wire:click.prevent="cancel"
          class="bg-green-300 px-2 py-1 rounded-xl"
        >Cancel</button>
      </div>
    @endif
  </form>
</article>
