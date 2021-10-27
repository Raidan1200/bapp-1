<div>
  <table class="table-fixed w-full">
    <thead>
      <tr class="bg-gray-100">
        <td class="w-5/12 border-r px-1 border-white">
          Paket
          @can('modify bookings')
            <button
              class="float-right p-2"
              @click="state.editing = ! state.editing"
            >
              <x-icons.edit height="3" :width="3" />
            </button>
          @endcan
        </td>
        <td class="w-1/12 text-center px-1 border-r border-white">Start</td>
        <td class="w-1/12 text-center px-1 border-r border-white">Ende</td>
        <td class="w-1/12 text-center px-1 border-r border-white">Flat</td>
        <td class="w-1/12 text-right px-1 border-r border-white">#</td>
        @can('modify bookings')
          <td class="w-1/12 text-right px-1 border-r border-white">Preis</td>
          <td class="w-1/12 text-right px-1 border-r border-white">MwSt</td>
          <td class="w-1/12 text-right px-1 border-r border-white">Anz.</td>

          <template x-if="state.editing">
            <td class="w-1/12 text-right px-1 font-semibold">
              Löschen
            </td>
          </template>
          <template x-if="!state.editing">
            <td class="w-1/12 text-right px-1">Gesamt</td>
          </template>
        @endcan
      </tr>
    </thead>
    <tbody>
      <template x-for="(booking, index) in order.bookings" :key="booking.data.id">
        {{-- Errors --}}

        {{-- When editing --}}
        <tr :class="colorForState(booking.state)">
          <td>
            <template x-if="state.editing">
              <x-input
                x-model="booking.data.package_name"
                class="w-full"
              />
            </template>
            <template x-if="! state.editing">
              <span x-text="booking.data.package_name"></span>
            </template>
          </td>
          <td>
            <template x-if="state.editing">
              <x-input
                x-model="booking.data.starts_at"
                class="w-full"
              />
            </template>
            <template x-if="! state.editing">
              <span x-text="booking.data.starts_at"></span>
            </template>
          </td>
          <td>
            <template x-if="state.editing">
              <x-input
                x-model="booking.data.ends_at"
                class="w-full"
              />
            </template>
            <template x-if="! state.editing">
              <span x-text="booking.data.ends_at"></span>
            </template>
          </td>
          <td>
            <template x-if="state.editing">
              <x-input
                type="checkbox"
                x-model="booking.data.is_flat"
                class="w-full"
              />
            </template>
            <template x-if="! state.editing && booking.data.is_flat">
              <x-icons.check />
            </template>
          </td>
          <td>
            <template x-if="state.editing">
              <x-input
                type="number"
                min="0" max=""
                x-model="booking.data.quantity"
                class="w-full"
              />
            </template>
            <template x-if="state.quantity">
            </template>
          </td>
          <td>
            <template x-if="state.editing">
              <x-input
                x-model="booking.data.unit_price"
                class="w-full"
              />
            </template>
            <template x-if="! state.editing">
              <span x-text="booking.data.unit_price"></span>
            </template>
          </td>
          <td>
            <template x-if="state.editing">
              <x-input
                x-model="booking.data.vat"
                class="w-full"
              />
            </template>
            <template x-if="! state.editing">
              <span x-text="booking.data.vat"></span>
            </template>
          </td>
          <td>
            <template x-if="!state.editing || order.deposit_paid_at">
              <span x-text="booking.data.deposit">%</span>
            </template>
            <template x-if="state.editing && !order.deposit_paid_at">
              <x-input
                x-model="booking.data.deposit"
                class="w-full"
              />
            </template>
          </td>
          <td class="text-right">
            <button type="button" @click="deleteClicked(index)">
              <x-icons.delete width="4" height="4" />
            </button>
          </td>
        </tr>
      </template>
    </tbody>
  </table>

  <template x-if="state.editing">
    <div class="flex justify-between p-4">
      <div>
        <x-button class="button" @click="addRow" >Position hinzufügen</x-button>
      </div>
      <div>
        <x-button class="button" @click="save">Speichern</x-button>
        <x-button class="button" @click="state.editing = false">Abbrechen</x-button>
      </div>
    </div>
  </template>
</div>