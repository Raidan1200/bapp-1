<x-app-layout>
  @isset ($venue)
    <h1 class="px-4 text-2xl">Veranstaltungsort {{ $venue->name }} editieren</h1>
  @else
    <h1 class="px-4 text-2xl">Neuen Veranstaltungsort anlegen</h1>
  @endisset

  <form
    method="POST"
    action="{{ isset($venue) ? route('venues.update', $venue) : route('venues.store') }}"
    class="px-4"
  >
    @csrf
    @isset ($venue)
      @method('PUT')
    @endisset

    <x-auth-validation-errors></x-auth-validation-errors>

    <div class="sm:flex sm:space-x-8">
      @isset ($venue)
        <div class="sm:w-1/2">
      @else
        <div class="w-full">
      @endif
        <x-form-field>
          <x-label for="name">Name</x-label>
          <x-input type="text" name="name" class="w-full" value="{{ old('name') ?? $venue->name ?? '' }}" id="name" />
        </x-form-field>

        <x-form-field>
          <x-label for="email">E-Mail</x-label>
          <x-input type="text" name="email" class="w-full" value="{{ old('email') ?? $venue->email ?? '' }}" id="email" />
        </x-form-field>

        <x-form-field>
          <x-label for="reminder_delay">Tage bis Erinnerungsmail</x-label>
          <x-input type="number" min="0" name="reminder_delay" class="w-full" value="{{ old('reminder_delay') ?? $venue->reminder_delay ?? '' }}"  id="reminder_delay" />
        </x-form-field>

        <x-form-field>
          <x-label for="check_delay">Tage bis Mitarbeiternotitz</x-label>
          <x-input type="number" min="0" name="check_delay" class="w-full" value="{{ old('check_delay') ?? $venue->check_delay ?? '' }}"  id="check_delay" />
        </x-form-field>

        <div class="mt-2 text-right">
          <a href="{{ route('venues.index') }}">
            <x-button type="button">Cancel</x-button>
          </a>
          <x-button>Save</x-button>
        </div>
      </div>
    </form>

    @isset ($venue)
      <div class="sm:w-1/2">
        <x-form-field>
          <livewire:venue-member :user="$user" id="venuemembers" />
        </x-form-field>

        <x-form-field class="mt-8 border-t border-gray-700 py-4">
          <livewire:profile-image :user="$user" id="profileimage" />
        </x-form-field>

        @if ($venue->image)
          <img src="{{ $venue->image }}" alt="">
        @endif
      @endisset
    </div>
  </div>
</x-app-layout>
