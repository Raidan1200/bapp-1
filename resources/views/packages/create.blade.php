<x-app-layout>
  @isset ($package)
    <h1 class="px-4 text-2xl">Paket {{ $package->name }} editieren</h1>
  @else
    <h1 class="px-4 text-2xl">Neues Paket anlegen</h1>
  @endisset

  <div class="sm:flex sm:space-x-8">
    <div class="sm:w-1/2 px-4">
      <form
        method="POST"
        action="{{ isset($package) ? route('packages.update', $package) : route('packages.store') }}"
      >
        @csrf
        @isset ($package)
          @method('PUT')
        @endisset

        <x-auth-validation-errors></x-auth-validation-errors>

        <input type="hidden" name="venue_id" value="{{ $package->venue_id ?? $venue_id }}" id="name" />

        <x-form-field>
          <x-label for="name">Name</x-label>
          <x-input type="text" name="name" class="w-full" value="{{ old('name') ?? $package->name ?? '' }}" id="name" />
        </x-form-field>

        <x-form-field>
          <x-label for="slogan">Slogan</x-label>
          <x-input type="text" name="slogan" class="w-full" value="{{ old('slogan') ?? $package->slogan ?? '' }}" id="slogan" />
        </x-form-field>

        <x-form-field>
          <x-label for="description">Beschreibung</x-label>
          <x-textarea name="description" class="w-full" id="description">
            {!! old('description') ?? $package->description ?? '' !!}
          </x-textarea>
        </x-form-field>

        <x-form-field>
          <x-label for="starts_at">Angeboten von</x-label>
          <x-input type="date" name="starts_at" class="w-full" value="{{ old('starts_at') ?? (isset($package) ? $package->starts_at->format('Y-m-d') : '') }}" id="starts_at" />
        </x-form-field>

        <x-form-field>
          <x-label for="capacity">Angeboten bis</x-label>
          <x-input type="date" name="ends_at" class="w-full" value="{{ old('ends_at') ?? (isset($package) ? $package->ends_at->format('Y-m-d') : '') }}" id="ends_at" />
        </x-form-field>

        <x-form-field>
          <x-label for="opens_at">Öffnungszeit von</x-label>
          <x-input type="text" name="opens_at" class="w-full" value="{{ old('opens_at') ?? $package->opens_at ?? '' }}" id="opens_at" />
        </x-form-field>

        <x-form-field>
          <x-label for="closes_at">Öffnungszeit bis</x-label>
          <x-input type="text" name="closes_at" class="w-full" value="{{ old('closes_at') ?? $package->closes_at ?? '' }}" id="closes_at" />
        </x-form-field>

        <x-form-field>
          <x-label for="min_occupancy">Mindestbelegung</x-label>
          <x-input type="number" min="0" name="min_occupancy" class="w-full" value="{{ old('min_occupancy') ?? $package->min_occupancy ?? '' }}" id="min_occupancy" />
        </x-form-field>

        <x-form-field>
          <x-label for="unit_price">Brutto Preis</x-label>
          <x-input type="number" min="0" name="unit_price" class="w-full" value="{{ old('unit_price') ?? $package->unit_price ?? '' }}" id="unit_price" />
        </x-form-field>

        <x-form-field>
          <x-label for="vat">MwSt</x-label>
          <x-input type="text" name="vat" class="w-full" value="{{ old('vat') ?? $package->vat ?? '' }}" id="vat" />
        </x-form-field>

        <x-form-field>
          <x-label for="deposit">Anzahlung</x-label>
          <x-input type="number" min="0" name="deposit" class="w-full" value="{{ old('deposit') ?? $package->deposit ?? '' }}" id="deposit" />
        </x-form-field>

        <x-form-field>
          <x-label for="is_flat">Preis ist Flatpreis
            <input
              {{ (old('is_flat') ?? $package->is_flat ?? false ) ? 'checked' : '' }}
              type="checkbox"
              name="is_flat"
              class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            >
          </x-label>
        </x-form-field>

        <div class="mt-2 text-right">
          <a href="{{ route('venues.show', $package->venue_id ?? $venue_id) }}">
            <x-button type="button">Cancel</x-button>
          </a>
          <x-button>Save</x-button>
        </div>
      </form>

      @isset ($package)
        @can('delete rooms')
          <div class="sm:text-right mt-8">
            <x-button
              type="button"
              class="hover:bg-red-500"
            >
              <div
                x-data
                @click.prevent="$dispatch('open-delete-modal', {
                  route: '{{ route('packages.destroy', $package) }}',
                  entity: '{{ $package->name }}',
                  subText: '',
                })"
              >
                Paket löschen
              </div>
            </x-button>
          </div>
        @endcan
      @endisset
    </div>
  </div>
</x-app-layout>
