<x-app-layout>
  <ul>
    @foreach ($users as $user)
      <li class="p-2 rounded-lg hover:bg-gray-200">
        <div class="flex justify-between">
          <div class="sm:w-2/5">
            {{ $user->name }}
          </div>
          <div class="hidden sm:block sm:w-3/6 text-sm text-gray-600">
            {{ $user->email }}
          </div>
          <div>
            <x-link :href="route('users.edit', $user->id)">
              <x-icons.edit />
            </x-link>
          </div>
        </div>
      </li>
    @endforeach
  </ul>

  @can('create users')
    <div class="mt-8">
      <a href="{{ route('users.create') }}" title="Neuen Benutzer anlegen">
        <x-button>Neuen Benutzer anlegen</x-button>
      </a>
    </div>
  @endcan
</x-app-layout>
