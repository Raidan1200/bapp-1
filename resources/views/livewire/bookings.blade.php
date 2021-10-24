<div>
  <table class="table-fixed w-full">
    <thead>
      <tr class="bg-gray-100">
        <td class="w-5/12 border-r px-1 border-white">
          Paket
          @can('modify bookings')
            @if(!$editing)
              <button
                class="float-right p-2"
                wire:click="startEditing"
              >
                <x-icons.edit height="3" :width="3" />
              </button>
            @endif
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
          @if ($editing)
            <td class="w-1/12 text-right px-1 font-semibold">
              Löschen
            </td>
          @else
            <td class="w-1/12 text-right px-1">Gesamt</td>
          @endif
        @endcan
      </tr>
    </thead>
    <tbody>
      @foreach ($bookings as $key => $booking)
        @if ($editing)
          @if ($errors->has("bookings.$key.*"))
            <tr>
              <td colspan="7" class="bg-red-300">
                @foreach ($errors->get("bookings.$key.*") as $error)
                  <div>{{ $error[0] ?? '' }}</div>
                @endforeach
              </td>
            </tr>
          @endif
          <tr
            wire:key="{{ $key }}"
            class="
              {{ $booking['state'] === 'delete' ? 'bg-red-200' : '' }}
              {{ $booking['state'] === 'new' ? 'bg-green-200' : '' }}
            "
          >
            <td>
              <x-input
                wire:model="bookings.{{ $key }}.data.package_name"
                class="w-full"
              />
              @if (count($foundPackages) && $key == $row)
                <ul class="absolute bg-white z-10">
                  @foreach ($foundPackages as $package)
                    <li
                      wire:click="fillFields({{ $row }}, {{ $package }})"
                      class="m-2"
                    >
                      {{ $package->name }}
                    </li>
                  @endforeach
                </ul>
              @endif
            </td>
            <td>
              <x-input
                wire:model.defer="bookings.{{ $key }}.starts_time"
                class="w-full"
              />
            </td>
            <td>
              <x-input
                wire:model.defer="bookings.{{ $key }}.ends_time"
                class="w-full"
              />
            </td>
            <td class="flex justify-center">
              <x-input
                type="checkbox"
                wire:model.defer="bookings.{{ $key }}.data.is_flat"
              />
            </td>
            <td class="text-right">
              <x-input
                wire:model.defer="bookings.{{ $key }}.data.quantity"
                class="w-full"
              />
            </td>
            <td class="text-right">
              <x-input
                wire:model.defer="bookings.{{ $key }}.data.unit_price"
                class="w-full"
              />
            </td>
            <td class="text-right">
              <x-input
                wire:model.defer="bookings.{{ $key }}.data.vat"
                class="w-full"
              />
            </td>
            <td class="text-right">
              @if ($order->deposit_paid_at)
                <span >{{ $bookings['data'][$key]['deposit'] }}%</span>
              @else
                <x-input
                  wire:model.defer="bookings.{{ $key }}.data.deposit"
                  class="w-full"
                />
              @endif
            </td>
            <td class="text-right">
              <button wire:click="toggleDelete({{ $key }})">
                <x-icons.delete width="4" height="4" />
              </button>
            </td>
          </tr>
        @else
          <tr>
            <td>
              {{ $booking['data']['package_name'] }}
            </td>
            <td class="text-right">
              {{ $booking['starts_time'] }}
            </td>
            <td class="text-right">
              {{ $booking['ends_time'] }}
            </td>
            <td class="flex justify-center">
              @if ($booking['data']['is_flat'])
                <x-icons.check />
              @endif
            </td>
            <td class="text-right">
              {{ $booking['data']['quantity'] }}
            </td>
            @can('modify bookings')
              <td class="text-right">
                {{-- TODO!!! Livewire sucks!!! $booking is not a Model but an Array so all Accessors are BOOM --}}
                {{
                  money(
                    $booking['data']['interval']
                      ? $booking['data']['unit_price'] * (new Carbon\Carbon($booking['data']['starts_at']))->diffInMinutes(new Carbon\Carbon($booking['data']['ends_at'])) / $booking['data']['interval']
                      : $booking['data']['unit_price']
                  )
                }}
              </td>
              <td class="text-right">
                {{ $booking['data']['vat'] }}%
              </td>
              <td class="text-right">
                {{ $booking['data']['deposit'] }}%
              </td>
              <td class="text-right">
                {{-- TODO!!! Livewire sucks!!! $booking is not a Model but an Array so all Accessors are BOOM --}}
                {{
                  money(
                    $booking['data']['interval']
                      ? $booking['data']['unit_price'] * (new Carbon\Carbon($booking['data']['starts_at']))->diffInMinutes(new Carbon\Carbon($booking['data']['ends_at'])) / $booking['data']['interval'] * $booking['data']['quantity']
                      : $booking['data']['unit_price'] * $booking['data']['quantity']
                  )
                }}
              </td>
            @endcan
          </tr>
        @endif
      @endforeach
    </tbody>
  </table>
  @if ($editing)
    <div class="flex justify-between pb-4">
      <div>
        <x-button wire:click="addRow" >Position hinzufügen</x-button>
      </div>
      <div>
        <x-button wire:click="save">Speichern</x-button>
        <x-button class="button" wire:click="cancel">Abbrechen</x-button>
      </div>
    </div>
  @endif
</div>
