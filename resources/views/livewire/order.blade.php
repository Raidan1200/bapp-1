<article
  x-data="{
    showCustomer: false,
    dirty: @entangle('dirty')
  }"
  class="sm:m-2 sm:p-2 lg:p-4 bg-white rounded-xl shadow text-sm sm:text-base border-l-4 {{ $this->color }}"
>
  {{-- Headline --}}
  <div class="flex justify-between">
    <button
      @click="showCustomer = !showCustomer"
      class="flex-1 text-left hover:text-primary-dark rounded px-2 -mx-2 py-1 font-semibold"
    >
      @isset ($order->customer->company)
        {{ "{$order->customer->company} ({$order->customer->name})" }}
      @else
        {{ $order->customer->name }}
      @endisset
      ({{ $order->final_invoice_id ?? $order->interim_invoice_id ?? $order->deposit_invoice_id ?? 'Noch keine Rechnung' }})
    </button>
    <div>
      <div class="font-semibold text-right">{{ $order->starts_at->timezone('Europe/Berlin')->formatLocalized('%a %d.%m %H:%M') }}</div>
      @isset ($order->latestAction)
        <div title="Von: '{{ $order->latestAction->from }}' Zu: '{{ $order->latestAction->to }}'">
          <span>{{ $order->latestAction->created_at->diffForHumans() }}</span>:
          <span class="font-semibold">{{ $order->latestAction->user_name }}</span>: {{ $order->latestAction->what }}
        </div>
      @endisset
    </div>
  </div>
  <div x-cloak class="bg-primary-light" x-show="showCustomer">
    <livewire:customer
      :customer="$order->customer"
    />
  </div>
  <div class="mt-2">
    <livewire:bookings
      :bookings="$order->bookings->toArray()"
      :order="$order"
    />
  </div>
  <div class="mt-2">
      <livewire:items
        :items="$order->items->toArray()"
        :order="$order"
      />
  </div>
  <form
    wire:submit.prevent="save"
    action="#"
  >
    <div class="flex justify-between mt-2">
      {{-- Note --}}
      <div
        class="flex-1 mr-4"
      >
          @can('modify orders')
            <button
              x-show="!dirty"
              type="button"
              class="float-right"
              @click="dirty = true"
            >
              <x-icons.edit />
            </button>
          @endcan
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
              {{-- LATER: Find a more elegant solution ... please :) --}}
              <option value="fresh"
                {{ (in_array($order->state, ['deposit_paid', 'interim_paid', 'final_paid', 'cancelled'])) && auth()->user()->cannot('admin orders') ? 'disabled' : '' }}
              >
                Nicht bestätigt
              </option>
              <option value="deposit_paid"
                {{ (in_array($order->state, ['interim_paid', 'final_paid', 'cancelled'])) && auth()->user()->cannot('admin orders') ? 'disabled' : '' }}
              >
                Anzahlung eingegangen
                @if ($this->order->deposit_paid_at)
                  &#10003;
                @endif
              </option>
              <option value="interim_paid"
                {{ (in_array($order->state, ['final_paid', 'cancelled'])) && auth()->user()->cannot('admin orders') || ! $this->order->deposit_paid_at ? 'disabled' : '' }}
              >
                Abschlussrechnung bezahlt
                @if ($this->order->interim_paid_at)
                  &#10003;
                @endif
              </option>
              <option value="final_paid"
                {{ $this->order->interim_is_final || (in_array($order->state, ['cancelled'])) && auth()->user()->cannot('admin orders') ? 'disabled' : '' }}
              >
                Gesamtrechnung bezahlt
                @if ($this->order->final_paid_at)
                  &#10003;
                @endif
              </option>
              <option value="cancelled">Storniert</option>
              <option value="not_paid">Nicht bezahlt</option>
            </select>
          @else
            <div class="w-64">
              {{ __('app.'.$selectedState) }}
            </div>
          @endcan
        </div>
        <div>
          <div>Anzahlung: {{ money($this->order->deposit_amount) }}</div>
          <div>Gesamt: {{ money($this->order->grossTotal) }}</div>
          @can('modify orders')
            <div>
              <input
                type="checkbox"
                wire:model="cash"
                id="cash"
              />
              <label for="cash">Cash</label>
            </div>
          @endcan
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
                  entity: '{{ "von {$order->customer->name}" }}',
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
  @can('modify orders')
    <div class="flex justify-between">
      <div class="flex">
        <x-dropdown align="left">
          <x-slot name="trigger">
            <button class="flex items-center mr-4 hover:bg-gray-100 transition duration-150 ease-in-out">
              <div>Rechnungen</div>
              <div class="ml-1">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            <div
              wire:click="makeInvoice('deposit')"
              class="m-2 cursor-pointer"
            >
              Anzahlung
              @if ($order->deposit_invoice_at)
                <span>&#10003;</span>
              @endif
            </div>
            @if ($order->deposit_paid_at)
              <div
                wire:click="makeInvoice('interim')"
                class="m-2 cursor-pointer"
              >
                Abschluss
                @if ($order->interim_invoice_at)
                  <span>&#10003;</span>
                @endif
              </div>
            @endif
            @if (! $order->interim_is_final)
              <div
                wire:click="makeInvoice('final')"
                class="m-2 cursor-pointer"
              >
                Gesamt
                @if ($order->final_invoice_at)
                  <span>&#10003;</span>
                @endif
              </div>
            @endif
            {{-- TODO TODO --}}
            <div
              wire:click="makeInvoice('cancelled')"
              class="m-2 cursor-pointer"
            >
              Storno
            </div>
          </x-slot>
        </x-dropdown>
        <x-dropdown align="left">
          <x-slot name="trigger">
            <button class="flex items-center hover:bg-gray-100 transition duration-150 ease-in-out">
              <div>Emails</div>
              <div class="ml-1">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            <div
              wire:click="sendEmail('deposit')"
              class="m-2 cursor-pointer"
            >
              Anzahlung
              @if ($order->deposit_email_at)
                <span>&#10003;</span>
              @endif
            </div>
              <div
                wire:click="sendEmail('interim')"
                class="m-2 cursor-pointer"
              >
                Abschluss
                @if ($order->interim_email_at)
                  <span>&#10003;</span>
                @endif
              </div>
            @unless ($order->interim_is_final)
              <div
                wire:click="sendEmail('final')"
                class="m-2 cursor-pointer"
              >
                Gesamt
                @if ($order->final_email_at)
                  <span>&#10003;</span>
                @endif
              </div>
            @endunless
            <div
              wire:click="sendEmail('cancelled')"
              class="m-2 cursor-pointer"
            >
              Stornierung
              @if ($order->cancelled_at)
                <span>&#10003;</span>
              @endif
            </div>
          </x-slot>
        </x-dropdown>
      </div>
    </div>
  @endcan
</article>
