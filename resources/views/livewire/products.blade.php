<div>
  @can('create products')
    @if ($adding)
      <form class="flex justify-between border-b ">
        <x-form-field>
          <x-label for="name">Produktname</x-label>
          <x-input wire:model.defer="newProduct.name" />
          <div>{{ $errors->first("newProduct.name") }}</div>
        </x-form-field>
        <x-form-field>
          <x-label for="name">Bruttopreis</x-label>
          <x-input wire:model.defer="newProduct.unit_price" />
          <div>{{ $errors->first("newProduct.unit_price") }}</div>
        </x-form-field>
        <x-form-field>
          <x-label for="name">MwSt</x-label>
          <x-input wire:model.defer="newProduct.vat" />
          <div>{{ $errors->first("newProduct.vat") }}</div>
      </x-form-field>
        <x-form-field>
          <x-button wire:click.prevent="store">Speichern</x-button>
          <x-button wire:click.prevent="cancelAdd">Abbrechen</x-button>
        </x-form-field>
      </form>
    @else
      <x-button wire:click="add">Produkt hinzufügen</x-button>
    @endif
  @endcan
  <table class="mt-4">
    <thead>
      <tr>
        <td>Produktname</td>
        <td>Brutto-Preis</td>
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
              <x-button wire:click="save({{ $index }})">Speichern</x-button>
              <x-button wire:click="cancelEdit">Abbrechen</x-button>
            </td>
          @else
            <td>{{ $product['name'] }}</td>
            <td>{{ money($product['unit_price']) }}</td>
            <td>{{ $product['vat'] }}</td>
            <td>
              @can('modify products')
                <x-button wire:click="edit({{ $index }})">Editieren</x-button>
              @endcan
              @can('delete products')
                <x-button wire:click="delete({{ $index }})">Löschen</x-button>
              @endcan
            </td>
          @endif
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
