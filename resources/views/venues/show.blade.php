<x-app-layout>
  <h1 class="text-2xl">{{ $venue->name }}</h1>

  <div class="sm:flex sm:space-x-8">
    <div class="w-full sm:w-1/2">
      <div class="flex my-2">
        <h2 class="text-xl">Räume</h2>
        <x-link href="{{ route('rooms.create', ['venue' => $venue->id]) }}">
          <x-icons.add class="h-6 w-6" />
        </x-link>
      </div>
      @if ($venue->rooms)
        <ul class="m-2">
          @foreach ($venue->rooms as $room)
            <li>
              <x-link href="{{ route('rooms.edit', $room) }}">
                {{ $room->name }}
              </x-link>
            </li>
          @endforeach
        </ul>
      @else
        <div class="m-2">
          Dieser Veranstaltungsort hat noch keine Räume
        </div>
      @endif

      <div class="flex my-2">
        <h2 class="text-xl my-2">Produkte</h2>
        <x-link href="{{ route('products.create', ['venue' => $venue->id]) }}">
          <x-icons.add class="h-6 w-6" />
        </x-link>
      </div>
      @if ($venue->products)
        <ul class="m-2">
          @foreach ($venue->products as $product)
            <li>
              <x-link href="{{ route('products.edit', $product) }}">
                {{ $product->name }}
              </x-link>
            </li>
          @endforeach
        </ul>
      @else
        <div class="m-2">
          Dieser Veranstaltungsort hat noch keine Produkte
        </div>
      @endif
    </div>

    <div class="w-full sm:w-1/2">
      <h2 class="text-xl my-2">Zugeorndete Benutzer</h2>
      @if ($venue->users)
        <ul>
          @foreach ($venue->users as $user)
            <li>{{ $user->name }}</li>
          @endforeach
        </ul>
      @else
        <div class="m-2">
          Es wurde noch keine Benutzer zugeordnet
        </div>
      @endif
    </div>
  </div>

  @can('delete venues')
    <div class="sm:text-right mt-8">
      <x-button
        type="button"
        class="hover:bg-red-500"
      >
        <div
          x-data
          @click.prevent="$dispatch('open-delete-modal', {
            route: '{{ route('venues.destroy', $venue) }}',
            entity: '{{ $venue->name }}',
            subText: '',
          })"
        >
          Veranstaltungsort löschen
        </div>
      </x-button>
    </div>
  @endcan

</x-app-layout>
