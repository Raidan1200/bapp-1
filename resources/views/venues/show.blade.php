<x-app-layout>
  @can('delete venues')
    <div class="sm:float-right">
      @csrf
      @method('delete')
        <button
          type="button"
          class="hover:bg-red-500"
          x-data
          @click.prevent="$dispatch('open-delete-modal', {
            route: '{{ route('venues.destroy', $venue) }}',
            entity: '{{ $venue->name }}',
            subText: '',
          })"
        >
          Veranstaltungsort löschen
        </button>
      </div>
  @endcan

  <h1 class="text-2xl">{{ $venue->name }}</h1>

  <div class="sm:flex sm:flex-col sm:space-x-8">
    <ul class="sm:w-1/2">
      @forelse ($venue->rooms as $room)
        {{ $room->name }}
      @empty
        Dieser Veranstaltungsort hat noch keine Räume
      @endforelse
    </ul>

    <ul class="sm:w-1/2">
      @forelse ($venue->products as $room)
        {{ $room->products }}
      @empty
        Dieser Veranstaltungsort hat noch keine Produkte
      @endforelse
    </ul>
  </div>
</x-app-layout>
