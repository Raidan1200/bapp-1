<article
  x-data="{
    editCustomer: false,
    dirty: @entangle('dirty')
  }"
  class="sm:m-2 sm:p-2 lg:p-4 bg-white rounded-xl shadow text-sm sm:text-base border-l-4 {{ $this->color }}"
>
  {{-- Headline --}}
  <div class="flex justify-between">
    <button
      @click="editCustomer = !editCustomer"
      class="flex-1 text-left hover:text-primary-dark rounded px-2 -mx-2 py-1 font-semibold"
    >
      {{ $order->customer->name }}
    </button>
    <div>
      <div class="font-semibold text-right">{{ $order->starts_at->timezone('Europe/Berlin')->formatLocalized('%a %d.%m %H:%M') }}</div>
      @isset ($order->latestAction)
        <div>
          <span>{{ $order->latestAction->created_at->diffForHumans() }}</span>:
          <span class="font-semibold">{{ $order->latestAction->user_name }}</span>: {{ $order->latestAction->message }}
        </div>
      @endisset
    </div>
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
        @can('modify orders')
          @click="dirty = true"
        @endcan
        class="flex-1 mr-4"
      >
        <textarea
          x-cloak
          x-show="dirty"
          wire:model.defer="notes"
          class="w-full"
          name="order-notes"
          id="order-notes"
        >{{ $notes }}</textarea>
        <div
          x-show="!dirty"
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
          @can('modify orders')
            <select
              wire:model="selectedState"
              class="py-0"
              name="order-status"
              id="order-status"
            >
              {{-- TODO: Find a more elegant solution ... please :) --}}
              <option value="fresh"
                {{ (in_array($order->state, ['deposit_paid', 'interim_paid', 'final_paid', 'cancelled'])) && auth()->user()->cannot('admin orders') ? 'disabled' : '' }}
              >Nicht bestätigt</option>
              <option value="deposit_paid"
                {{ (in_array($order->state, ['interim_paid', 'final_paid', 'cancelled'])) && auth()->user()->cannot('admin orders') ? 'disabled' : '' }}
              >Anzahlung eingegangen</option>
              <option value="interim_paid"
                {{ (in_array($order->state, ['final_paid', 'cancelled'])) && auth()->user()->cannot('admin orders') ? 'disabled' : '' }}
              >Zwischenrechnung bezahlt</option>
              <option value="final_paid"
                {{ (in_array($order->state, ['cancelled'])) && auth()->user()->cannot('admin orders') ? 'disabled' : '' }}
              >Schlussrechnung bezahlt</option>
              <option value="cancelled">Storniert</option>
            </select>
          @else
            {{ $selectedState }}
          @endcan
        </div>
        <div>
          <div>Anzahlung: {{ number_format($this->deposit / 100, 2, ',', '.') }}</div>
          <div>Gesamt: {{ number_format($this->total / 100, 2, ',', '.') }}</div>
        </div>
      </div>
    </div>
    @if ($dirty)
      <div class="flex justify-between mt-4">
        <div>
          @can('delete orders')
            <x-button
              type="button"
              class="bg-red-400 hover:bg-red-600"
            >
              <div
                x-data
                @click.prevent="$dispatch('open-delete-modal', {
                  route: '{{ route('orders.destroy', $order) }}',
                  entity: '{{ "von {$order->customer->first_name} {$order->customer->last_name}" }}',
                  subText: '',
                })"
              >
                Bestellung löschen
              </div>
            </x-button>
          @endcan
        </div>
        <div>
          <x-button class="bg-green-500 hover:bg-green-600">Save</x-button>
          <x-button
            wire:click.prevent="cancel"
            class="bg-yellow-500 hover:bg-yellow-600"
          >Cancel</x-button>
        </div>
      </div>
    @endif
  </form>
</article>
