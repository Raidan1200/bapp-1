<x-app-layout>
  <h1 class="text-2xl">{{ $venue->name }}</h1>

  <div class="sm:flex sm:space-x-8">
    <div class="w-full sm:w-1/2">
      <div class="flex my-2">
        <h2 class="text-xl">Räume</h2>
        @can('create rooms')
          <x-link href="{{ route('rooms.create', ['venue' => $venue->id]) }}">
            <x-icons.add class="h-6 w-6" />
          </x-link>
        @endcan
      </div>
      @if ($venue->rooms)
        <ul class="m-2">
          @foreach ($venue->rooms as $room)
            <li>
              @can('modify rooms')
                <x-link href="{{ route('rooms.edit', $room) }}">
                  {{ $room->name }}
                </x-link>
              @else
                {{ $room->name }}
              @endcan
            </li>
          @endforeach
        </ul>
      @else
        <div class="m-2">
          Dieser Veranstaltungsort hat noch keine Räume
        </div>
      @endif

      <div class="flex my-2">
        <h2 class="text-xl my-2">Pakete</h2>
        @can('create packages')
          {{-- LATER: Somehow I don't like the Query String Param. Use a REST route instead? /venues/1/products/create ? --}}
          <x-link href="{{ route('packages.create', ['venue' => $venue->id]) }}">
            <x-icons.add class="h-6 w-6" />
          </x-link>
        @endcan
      </div>
      @if ($venue->packages)
        <ul class="m-2">
          @foreach ($venue->packages as $package)
            <li>
              @can('modify packages')
                <x-link href="{{ route('packages.edit', $package) }}">
                  {{ $package->name }}
                </x-link>
              @else
                {{ $package->name }}
              @endcan
            </li>
          @endforeach
        </ul>
      @else
        <div class="m-2">
          Dieser Veranstaltungsort hat noch keine Pakete
        </div>
      @endif

      <div class="flex my-2">
        <h2 class="text-xl my-2">Produkte</h2>
        @canany('create products', 'modify products', 'delete products')
          {{-- LATER: Somehow I don't like the Query String Param. Use a REST route instead? /venues/1/products/create ? --}}
          <x-link href="{{ route('products', ['venue' => $venue->id]) }}">
            <x-icons.add class="h-6 w-6" />
          </x-link>
        @endcanany
      </div>
      @if ($venue->products)
        <ul class="m-2">
          @foreach ($venue->products as $product)
            <li>
              {{ $product->name }}
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

  @can('modify venues')
  <div class="sm:text-right mt-8">
    <a href="{{ route('venues.edit', $venue->id) }}" title="{{ $venue->name }} editieren">
        <x-button>{{ $venue->name }} editieren</x-button>
      </a>
    </div>
  @endcan
</x-app-layout>
