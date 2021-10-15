<x-app-layout>
  <div class="px-4">
    @isset ($room)
      <h1 class="text-2xl">Raum {{ $room->name }} editieren</h1>
      <x-link class="text-sm" href="{{ route('venues.show', $room->venue_id) }}">zurück</x-link>
    @else
      <h1 class="text-2xl">Neuen Raum anlegen</h1>
      <x-link class="text-sm" href="{{ route('venues.show', $venue_id) }}">zurück</x-link>
    @endisset
  </div>

  <div class="sm:flex sm:space-x-8">
    <div class="sm:w-1/2 px-4">
      <form
        method="POST"
        action="{{ isset($room) ? route('rooms.update', $room) : route('rooms.store') }}"
      >
        @csrf
        @isset ($room)
          @method('PUT')
        @endisset

        <x-auth-validation-errors></x-auth-validation-errors>

        <input type="hidden" name="venue_id" value="{{ $room->venue_id ?? $venue_id }}" id="name" />

        <x-form-field>
          <x-label for="name">Name</x-label>
          <x-input type="text" name="name" class="w-full" value="{{ old('name') ?? $room->name ?? '' }}" id="name" />
        </x-form-field>

        <x-form-field>
          <x-label for="slug">Slug</x-label>
          <x-input type="text" name="slug" class="w-full" value="{{ old('slug') ?? $room->slug ?? '' }}" id="slug" />
        </x-form-field>

        <x-form-field>
          <x-label for="slogan">Slogan</x-label>
          <x-input type="text" name="slogan" class="w-full" value="{{ old('slogan') ?? $room->slogan ?? '' }}" id="slogan" />
        </x-form-field>

        <x-form-field>
          <x-label for="description">Beschreibung</x-label>
          <x-textarea name="description" class="w-full" id="description">
            {!! old('description') ?? $room->description ?? '' !!}
          </x-textarea>
        </x-form-field>

        <x-form-field>
          <x-label for="capacity">Kapazität</x-label>
          <x-input type="number" min="0" name="capacity" class="w-full" value="{{ old('capacity') ?? $room->capacity ?? '' }}" id="capacity" />
        </x-form-field>

        <x-form-field>
          <x-label for="image">Bildpfad</x-label>
          <x-input type="text" name="image" class="w-full" value="{{ old('image') ?? $room->image ?? '' }}" id="image" />
        </x-form-field>

        <div class="mt-2 text-right">
          <a href="{{ route('venues.show', $room->venue_id ?? $venue_id) }}">
            <x-button type="button">Cancel</x-button>
          </a>
          <x-button>Save</x-button>
        </div>
      </form>

      @isset ($room)
        @can('delete rooms')
          <div class="sm:text-right mt-8">
            <x-button
              type="button"
              class="hover:bg-red-500"
            >
              <div
                x-data
                @click.prevent="$dispatch('open-delete-modal', {
                  route: '{{ route('rooms.destroy', $room) }}',
                  entity: '{{ $room->name }}',
                  subText: '',
                })"
              >
                Raum löschen
              </div>
            </x-button>
          </div>
        @endcan
      @endisset
    </div>

    @isset ($room)
      <div class="sm:w-1/2">
        <x-form-field>
          <livewire:package-room :room="$room" id="packageroom" />
        </x-form-field>
      </div>
    @endisset
  </div>
</x-app-layout>
