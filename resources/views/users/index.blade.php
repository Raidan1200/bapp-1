<x-app-layout>
  @can('create users')
    <div class="mb-4">
      <a href="{{ route('users.create') }}" title="Neuen Benutzer anlegen">
        <x-button>Neuen Benutzer anlegen</x-button>
      </a>
    </div>
  @endcan

  <ul>
    @foreach ($users as $user)
      <li
        x-data="{ open: false }"
        class="p-2 rounded-lg hover:bg-gray-200""
        @click="open = ! open"
      >
        <div class="flex justify-between">
          <div>
            <span>{{ $user->name }}</span>
            <span class="hidden hover:block">{{ $user->email }}</span>
          </div>
          <x-link
            :href="route('users.edit', $user->id)"
          >
            <x-icons.edit />
          </x-link>
        </div>
        <div
          x-show="open"
          class="px-4 py-2"
        >
          <ul>
            @foreach ($user->venues as $venue)
              <li>{{ $venue->name }}</li>
            @endforeach
          </ul>
        </div>
      </li>
    @endforeach
  </ul>
</x-app-layout>
