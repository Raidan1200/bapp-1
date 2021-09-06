<div>
  <table class="table-fixed w-full">
    <thead>
      <tr class="bg-gray-100">
        <td class="w-5/12 border-r px-1 border-white">
          Produkt
          @can('modify orders')
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
        <td class="w-1/12 text-right px-1 border-r border-white">#</td>
        <td class="w-1/12 text-right px-1 border-r border-white">Preis</td>
        <td class="w-1/12 text-right px-1 border-r border-white">MwSt</td>
        @if ($editing)
          <td class="w-2/12 text-right px-1 font-semibold">
            Löschen
          </td>
        @else
          <td class="w-2/12 text-right px-1">Gesamt</td>
        @endif
      </tr>
    </thead>
    @foreach ($items as $key => $item)
      @if ($editing)
        @if ($errors->has("items.$key.*"))
          <tr>
            <td colspan="7" class="bg-red-300">
              {{ var_dump($errors->all()) }}
              Es fehlen:
              @if ($errors->has("items.$key.product_name"))
                Paketname
              @endif
            </td>
          </tr>
        @endif
        <tr
          wire:key="{{ $key }}"
          class="{{ $item['state'] === 'delete' ? 'bg-red-200' : '' }} {{ $item['state'] === 'new' ? 'bg-green-200' : '' }}"
        >
          <td>
            <x-input
              wire:model.defer="items.{{ $key }}.product_name"
              class="w-full"
            />
          </td>
          <td class="text-right">
            <x-input
              wire:model.defer="items.{{ $key }}.quantity"
              class="w-full"
            />
          </td>
          <td class="text-right">
            <x-input
              wire:model.defer="items.{{ $key }}.unit_price"
              class="w-full"
            />
          </td>
          <td class="text-right">
            <x-input
              wire:model.defer="items.{{ $key }}.vat"
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
            {{ $item['product_name'] }}
          </td>
          <td class="text-right">
            {{ $item['quantity'] }}
          </td>
          <td class="text-right">
            {{ money($item['unit_price']) }}
          </td>
          <td class="text-right">
            {{ $item['vat'] }}%
          </td>
          <td class="text-right">
            {{ number_format($item['quantity'] * $item['unit_price'] / 100, 2, ',', '.') }}
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
