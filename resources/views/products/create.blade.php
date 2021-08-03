<x-app-layout>
  @isset ($product)
    <h1 class="px-4 text-2xl">Produkt {{ $product->name }} editieren</h1>
  @else
    <h1 class="px-4 text-2xl">Neues Produkt anlegen</h1>
  @endisset

  <div class="sm:flex sm:space-x-8">
    <div class="sm:w-1/2 px-4">
      <form
        method="POST"
        action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}"
      >
        @csrf
        @isset ($product)
          @method('PUT')
        @endisset

        <x-auth-validation-errors></x-auth-validation-errors>

        <input type="hidden" name="venue_id" value="{{ $product->venue_id ?? $venue_id }}" id="name" />

        <x-form-field>
          <x-label for="name">Name</x-label>
          <x-input type="text" name="name" class="w-full" value="{{ old('name') ?? $product->name ?? '' }}" id="name" />
        </x-form-field>

        <x-form-field>
          <x-label for="slogan">Slogan</x-label>
          <x-input type="text" name="slogan" class="w-full" value="{{ old('slogan') ?? $product->slogan ?? '' }}" id="slogan" />
        </x-form-field>

        <x-form-field>
          <x-label for="description">Beschreibung</x-label>
          <x-textarea name="description" class="w-full" id="description">
            {!! old('description') ?? $product->description ?? '' !!}
          </x-textarea>
        </x-form-field>

        <x-form-field>
          <x-label for="starts_at">Angeboten von</x-label>
          <x-input type="date" name="starts_at" class="w-full" value="{{ old('starts_at') ?? $product->starts_at ?? '' }}" id="starts_at" />
        </x-form-field>

        <x-form-field>
          <x-label for="capacity">Angeboten bis</x-label>
          <x-input type="date" name="ends_at" class="w-full" value="{{ old('ends_at') ?? $product->ends_at ?? '' }}" id="ends_at" />
        </x-form-field>

        <x-form-field>
          <x-label for="opens_at">Öffnungszeit von</x-label>
          <x-input type="text" name="opens_at" class="w-full" value="{{ old('opens_at') ?? $product->opens_at ?? '' }}" id="opens_at" />
        </x-form-field>

        <x-form-field>
          <x-label for="closes_at">Öffnungszeit bis</x-label>
          <x-input type="text" name="closes_at" class="w-full" value="{{ old('closes_at') ?? $product->closes_at ?? '' }}" id="closes_at" />
        </x-form-field>

        <x-form-field>
          <x-label for="min_occupancy">Mindestbelegung</x-label>
          <x-input type="number" min="0" name="min_occupancy" class="w-full" value="{{ old('min_occupancy') ?? $product->min_occupancy ?? '' }}" id="min_occupancy" />
        </x-form-field>

        <x-form-field>
          <x-label for="unit_price">Preis</x-label>
          <x-input type="number" min="0" name="unit_price" class="w-full" value="{{ old('unit_price') ?? $product->unit_price ?? '' }}" id="unit_price" />
        </x-form-field>

        <x-form-field>
          <x-label for="vat">MwSt</x-label>
          <x-input type="text" name="vat" class="w-full" value="{{ old('vat') ?? $product->vat ?? '' }}" id="vat" />
        </x-form-field>

        <x-form-field>
          <x-label for="deposit">Anzahlung</x-label>
          <x-input type="number" min="0" name="deposit" class="w-full" value="{{ old('deposit') ?? $product->deposit ?? '' }}" id="deposit" />
        </x-form-field>

        <x-form-field>
          <x-label for="is_flat">Preis ist Flatpreis</x-label>
            <input
              {{ (old('deposit') ?? $product->deposit ?? false ) ? 'checked' : '' }}
              type="checkbox"
              name="is_flat"
              id="remember_me"
              class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
          </label>
          </x-form-field>

        <div class="mt-2 text-right">
          <a href="{{ route('venues.show', $product->venue_id ?? $venue_id) }}">
            <x-button type="button">Cancel</x-button>
          </a>
          <x-button>Save</x-button>
        </div>
      </form>

      @isset ($product)
        @can('delete rooms')
          <div class="sm:text-right mt-8">
            <x-button
              type="button"
              class="hover:bg-red-500"
            >
              <div
                x-data
                @click.prevent="$dispatch('open-delete-modal', {
                  route: '{{ route('products.destroy', $product) }}',
                  entity: '{{ $product->name }}',
                  subText: '',
                })"
              >
                Produkt löschen
              </div>
            </x-button>
          </div>
        @endcan
      @endisset
    </div>

    {{-- @isset ($product)
      <div class="sm:w-1/2">
        <x-form-field>
          <livewire:product-room :user="$product" id="productroom" />
        </x-form-field>
      </div>
    @endisset --}}
  </div>
</x-app-layout>
