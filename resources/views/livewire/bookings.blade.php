<div>
  <table class="table-fixed w-full">
    <thead>
      <tr class="bg-gray-100">
        <td class="w-5/12 border-r px-1 border-white">
          Produkt
          @if(!$editing)
            <button
              class="float-right p-2"
              wire:click="startEditing"
            >
              <x-icons.edit height="3" :width="3" />
            </button>
          @endif
        </td>
        <td class="w-1/12 text-center px-1 border-r border-white">Flat</td>
        <td class="w-1/12 text-right px-1 border-r border-white">#</td>
        <td class="w-1/12 text-right px-1 border-r border-white">Preis</td>
        <td class="w-1/12 text-right px-1 border-r border-white">MwSt</td>
        <td class="w-1/12 text-right px-1 border-r border-white">Anz.</td>
        @if ($editing)
        <td class="w-2/12 text-right px-1 font-semibold">
          Löschen
        </td>
        @else
          <td class="w-2/12 text-right px-1">Gesamt</td>
        @endif
      </tr>
    </thead>
    @foreach ($bookings as $key => $booking)
      @if ($editing)
        @if ($errors->has("bookings.$key.*"))
          <tr>
            <td colspan="7" class="bg-red-300">
              {{ var_dump($errors->all()) }}
              Es fehlen:
              @if ($errors->has("bookings.$key.product_name"))
                Produktname
              @endif
            </td>
          </tr>
        @endif
        <tr
          wire:key="{{ $key }}"
          class="{{ $booking['status'] === 'delete' ? 'bg-red-200' : '' }} {{ $booking['status'] === 'new' ? 'bg-green-200' : '' }}"
        >
          <td>
            <x-input
              wire:model.defer="bookings.{{ $key }}.product_name"
              class="w-full"
            />
          </td>
          <td class="flex justify-center">
            <x-input
              type="checkbox"
              wire:model.defer="bookings.{{ $key }}.is_flat"
            />
          </td>
          <td class="text-right">
            <x-input
              wire:model.defer="bookings.{{ $key }}.quantity"
              class="w-full"
            />
          </td>
          <td class="text-right">
            <x-input
              wire:model.defer="bookings.{{ $key }}.unit_price"
              class="w-full"
            />
          </td>
          <td class="text-right">
            <x-input
              wire:model.defer="bookings.{{ $key }}.vat"
              class="w-full"
            />
          </td>
          <td class="text-right">
            <x-input
              wire:model.defer="bookings.{{ $key }}.deposit"
              class="w-full"
            />
          </td>
          <td class="text-right">
            <button wire:click="removeRow({{ $key }})">
              <x-icons.delete width="4" height="4" />
            </button>
          </td>
        </tr>
      @else
        <tr>
          <td>
            {{ $booking['product_name'] }}
          </td>
          <td class="flex justify-center">
            @if ($booking['is_flat'])
              <x-icons.check />
            @endif
          </td>
          <td class="text-right">
            {{ $booking['quantity'] }}
          </td>
          <td class="text-right">
            {{ number_format($booking['unit_price'] / 100, 2, ',', '.') }}
          </td>
          <td class="text-right">
            {{ $booking['vat'] }}%
          </td>
          <td class="text-right">
            {{ $booking['deposit'] }}%
          </td>
          <td class="text-right">
            {{ number_format($booking['quantity'] * $booking['unit_price'] / 100, 2, ',', '.') }}
          </td>
        </tr>
      @endif
    @endforeach
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