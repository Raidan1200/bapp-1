<x-app-layout>
  <ul>
    @foreach ($venues as $venue)
      <li class="p-2 rounded-lg hover:bg-gray-200">
        <div class="flex justify-between">
          <x-link :href="route('venues.show', $venue->id)">
            {{ $venue->name }}
          </x-link>
          <div>
            <x-link :href="route('venues.edit', $venue->id)">
              <x-icons.edit />
            </x-link>
          </div>
        </div>
      </li>
    @endforeach
  </ul>

  @can('create venues')
  <div class="mt-8">
    <a href="{{ route('venues.create') }}" title="Neuen Veranstaltungsort anlegen">
        <x-button>Neuen Veranstaltungsort anlegen</x-button>
      </a>
    </div>
  @endcan
</x-app-layout>
