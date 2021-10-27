<x-app-layout>

  @include('orders.script')

  <article
    x-data="{...orderEditor()}"
    x-spread="{...orderEditorSpread}"
    x-init="init"
    class="sm:m-2 sm:p-2 lg:p-4 bg-white rounded-xl shadow text-sm sm:text-base border-l-4"
  >
    {{-- <div x-text="JSON.stringify(orsder)"></div>
    <div x-text="JSON.stringify(state)"></div> --}}

    @include('orders.headline')

    {{-- Customer --}}
    <div
      x-show="state.showCustomer"
      class="bg-gray-200"
    >
      Customer
    </div>

    <template x-if="! state.loading">
      @include('orders.table')
    </template>

    {{-- <form class="px-4"> --}}

      {{-- <x-auth-validation-errors></x-auth-validation-errors> --}}

      <div class="w-full flex justify-between">

        {{-- Note --}}
        <div class="flex-1 mr-4">
          <textarea
            x-model="order.notes"
            class="w-full"
            name="order-notes"
            id="order-notes"
          ></textarea>
        </div>

        @include('orders.state')
      </div>

      @include('orders.footer')

      {{-- Buttons --}}
      <div>
        <div class="mt-2 text-right">
          <a href="{{ route('venues.index') }}">
            <x-button
              x-bind:disabled="state.loading"
              type="button"
            >Cancel</x-button>
          </a>
          <x-button
            x-bind:disabled="state.loading"
            @click.prevent="save"
            >Save</x-button>
        </div>

        @can('delete orders')
          <div class="sm:text-right mt-24">
            <x-button
              type="button"
              class="hover:bg-red-500"
            >
              <div
                x-data
                @click.prevent="$dispatch('open-delete-modal', {
                  route: '{{ route('orders.destroy', $order) }}',
                  entity: 'die Bestellung von {{ $order->customer->name }}',
                  subText: '',
                })"
              >
                Bestellung löschen
              </div>
            </x-button>
          </div>
        @endcan
      </div>
    {{-- </form> --}}
  </article>
</x-app-layout>
