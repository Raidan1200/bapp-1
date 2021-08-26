<div>
  @if ($adding)
    <x-input wire:model.defer="newProduct.name" />
    <div>{{ $errors->first("newProduct.name") }}</div>
    <x-input wire:model.defer="newProduct.unit_price" />
    <div>{{ $errors->first("newProduct.unit_price") }}</div>
    <x-input wire:model.defer="newProduct.vat" />
    <div>{{ $errors->first("newProduct.vat") }}</div>
    <x-button wire:click="store">Save</x-button>
    <x-button wire:click="cancelAdd">Cancel</x-button>
  @else
    <x-button wire:click="add">Add</x-button>
  @endif
  <table>
    <thead>
      <tr>
        <td>Produktname</td>
        <td>Preis</td>
        <td>MwSt</td>
        @if ($editingIndex)
          <td>Aktionen</td>
        @endif
      </tr>
    </thead>
    <tbody>
      @foreach ($products as $index => $product)
        @if ($editingIndex === $index)
          <tr class="hover:bg-gray-200">
            <td>
              <x-input wire:model.defer="products.{{ $index }}.name" />
              <div>{{ $errors->first("products.$index.name") }}</div>
            </td>
            <td>
              <x-input wire:model.defer="products.{{ $index }}.unit_price" />
              <div>{{ $errors->first("products.$index.unit_price") }}</div>
            </td>
            <td>
              <x-input wire:model.defer="products.{{ $index }}.vat" />
              <div>{{ $errors->first("products.$index.vat") }}</div>
            </td>
            <td>
              <x-button wire:click="save({{ $index }})">Save</x-button>
              <x-button wire:click="cancelEdit">Cancel</x-button>
            </td>
          @else
            <td>{{ $product['name'] }}</td>
            <td>{{ $product['unit_price'] }}</td>
            <td>{{ $product['vat'] }}</td>
            <td>
              <x-button wire:click="edit({{ $index }})">Edit</x-button>
              <x-button wire:click="delete({{ $index }})">Delete</x-button>
            </td>
          @endif
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
