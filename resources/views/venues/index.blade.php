<x-app-layout>
  @can('create venues')
    <div class="sm:float-right">
      <a href="{{ route('venues.create') }}" title="Neuen Veranstaltungsort anlegen">
        <x-button>Neuen Veranstaltungsort anlegen</x-button>
      </a>
    </div>
  @endcan

  <ul>
    @foreach ($venues as $venue)
      <li class="m-2">
        <x-link :href="route('venues.show', $venue->id)">
          {{ $venue->name }}
        </x-link>
      </li>
    @endforeach
  </ul>
</x-app-layout>
