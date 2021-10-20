<x-app-layout>
	<div class="lg:flex">
    <div class="lg:w-1/5">
      @include('dashboard.sidebar')
    </div>
    <div class="lg:w-4/5">
      <div class="flex justify-between">

        {{-- Location Filter Menu --}}
        <div>
          <div class="inline-block relative"
            x-data="{
              open: false,
              active: 'Alle'
            }"
            @click.away="open = false"
          >
            <button
              class="cursor-pointer bg-gray-100 text-gray-700 hover:text-black border border-gray-400 rounded px-2 py-1"
              @click="open = !open"
            >
            @php
              // LATER: How to do this in a cleaner way? This is kinda ugly!
              if ($filters['venue']) echo ($venue = $venues->first(fn($v) => $v->id == $filters['venue']))->name;
              if ($filters['room']) echo ' - ' . $venue->rooms->first(fn($r) => $r->id == $filters['room'])->name;
              if (!$filters['venue'] && !$filters['room']) echo 'Alle Orte';
            @endphp
            </button>
            <ul class="bg-white absolute left-0 mt-2 shadow rounded w-40 py-1 text-indigo-600"
              x-show="open"
              x-cloak
            >
              <li>
                <a href="{{ route('dashboard', array_filter(array_merge($filters, ['venue' => '', 'room' => '']))) }}"
                  class="py-1 px-3 block hover:bg-indigo-100"
                >Alle</a>
              </li>
              @foreach ($venues as $venue)
                <li>
                  <a href="{{ route('dashboard', array_filter(array_merge($filters, ['venue' => $venue->id, 'room' => '']))) }}"
                    class="py-1 px-3 block hover:bg-indigo-100"
                  >{{ $venue->name }}</a>
                  @if ($venue->rooms)
                    <ul class="pl-4">
                      @foreach ($venue->rooms as $room)
                        <li>
                          <a href="{{ route('dashboard', array_filter(array_merge($filters, ['venue' => $venue->id, 'room' => $room->id]))) }}"
                            class="py-1 px-3 block hover:bg-indigo-100"
                          >{{ $room->name }}</a>
                        </li>
                      @endforeach
                    </ul>
                  @endif
                </li>
              @endforeach
            </ul>
          </div>
        </div>

        {{-- State Filter Menu --}}
        <div>
          <div class="inline-block relative"
            x-data="{
              open: false,
            }"
            @click.away="open = false"
          >
            <button
              class="cursor-pointer bg-gray-100 text-gray-700 hover:text-black border border-gray-400 rounded px-2 py-1"
              @click="open = !open"
            >{{ Request::has('state') ? __('app.'.Request::query('state')) : 'Alle' }}</button>
            <ul class="bg-white absolute right-0 mt-2 shadow rounded w-40 py-1 text-indigo-600"
              x-show="open"
              x-cloak
            >
              <li>
                <a href="{{ route('dashboard', array_filter(array_merge($filters, ['state' => '']))) }}"
                  class="py-1 px-3 block hover:bg-indigo-100"
                >Alle</a>
              </li>
              <li>
                <a href="{{ route('dashboard', array_filter(array_merge($filters, ['state' => 'fresh']))) }}"
                  class="py-1 px-3 block hover:bg-indigo-100"
                >{{ __('app.fresh') }}</a>
              </li>
              <li>
                <a href="{{ route('dashboard', array_filter(array_merge($filters, ['state' => 'deposit_paid']))) }}"
                  class="py-1 px-3 block hover:bg-indigo-100"
                >{{ __('app.deposit_paid') }}</a>
              </li>
              <li>
                <a href="{{ route('dashboard', array_filter(array_merge($filters, ['state' => 'interim_paid']))) }}"
                  class="py-1 px-3 block hover:bg-indigo-100"
                >{{ __('app.interim_paid') }}</a>
              </li>
              <li>
                <a href="{{ route('dashboard', array_filter(array_merge($filters, ['state' => 'final_paid']))) }}"
                  class="py-1 px-3 block hover:bg-indigo-100"
                >{{ __('app.final_paid') }}</a>
              </li>
              <li>
                <a href="{{ route('dashboard', array_filter(array_merge($filters, ['state' => 'cancelled']))) }}"
                  class="py-1 px-3 block hover:bg-indigo-100"
                >{{ __('app.cancelled') }}</a>
              </li>
              <li>
                <a href="{{ route('dashboard', array_filter(array_merge($filters, ['state' => 'not_paid']))) }}"
                  class="py-1 px-3 block hover:bg-indigo-100"
                >{{ __('app.not_paid') }}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      @include('dashboard.main')
    </div>
	</div>
</x-app-layout>
