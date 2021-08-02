<x-app-layout>
  @isset ($user)
    <h1 class="px-4 text-2xl">Benutzer {{ $user->name }} editieren</h1>
  @else
    <h1 class="px-4 text-2xl">Neuen Benutzer anlegen</h1>
  @endisset

  <div class="sm:flex sm:space-x-8">
    {{-- Left --}}
    <div class="sm:w-1/2 ">
      <form
        method="POST"
        action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}"
        class="px-4"
      >
        @csrf
        @isset ($user)
          @method('PUT')
        @endisset

        <x-auth-validation-errors></x-auth-validation-errors>

        <x-form-field>
          <x-label for="name">Benutzername</x-label>
          <x-input type="text" name="name" class="w-full" value="{{ old('name') ?? $user->name ?? '' }}" id="name" />
        </x-form-field>

        <x-form-field>
          <x-label for="email">E-Mail</x-label>
          <x-input type="text" name="email" class="w-full" value="{{ old('email') ?? $user->email ?? '' }}" id="email" />
        </x-form-field>

        <x-form-field>
          <x-label for="password">Passwort</x-label>
          <x-input type="text" name="password" class="w-full" id="password" />
        </x-form-field>

        <x-form-field>
          <x-label for="password_confirmation">Passwort bestätigen</x-label>
          <x-input type="text" name="password_confirmation" class="w-full" id="password_confirmation" />
        </x-form-field>

        <x-form-field>
          <x-label for="role">Benutzerrolle</x-label>
          <select name="role" class="w-full" id="role">
            <option value="">-- Benutzerrolle auswählen --</option>
            @foreach ($roles as $role)
              <option
                value="{{ $role->id }}"
                @if (isset($user) && $user->hasRole($role->name))
                  selected
                @endif
              >
                {{ ucfirst($role->name) }}
              </option>
            @endforeach
          </select>
        </x-form-field>

        <div class="text-right mt-2">
          <div>
            <a href="{{ route('users.index') }}">
              <x-button type="button">Abbrechen</x-button>
            </a>
            <x-button>Speichern</x-button>
          </div>
        </div>
      </form>

      @isset($user)
        @can('delete users')
          <div class="text-right m-4">
            @csrf
            @method('delete')
            <x-button
              type="button"
              class="bg-red-300 hover:bg-red-600"
            >
              <div
                x-data
                @click.prevent="$dispatch('open-delete-modal', {
                  route: '{{ route('users.destroy', $user) }}',
                  entity: '{{ $user->name }}',
                  subText: '',
                })"
              >
                Benutzer löschen
              </div>
            </x-button>
          </div>
        @endcan
      @endisset
    </div>

    {{-- Right --}}
    <div class="sm:w-1/2">
      @isset($user)
        <x-form-field>
          <livewire:venue-member :user="$user" id="venuemembers" />
        </x-form-field>

        <x-form-field class="mt-8 border-t border-gray-700 py-4">
          <livewire:profile-image :user="$user" id="profileimage" />
        </x-form-field>

        @if ($user->image)
          <img src="{{ $user->image }}" alt="">
        @endif
      @endisset
    </div>
  </div>
</x-app-layout>
