<div class="text-xl font-semibold">
  <a href="{{ route('dashboard').'?from='.(new \Carbon\Carbon)->format('Y-m-d') }}">Today</a>
</div>
<div class="text-xl font-semibold">
  <a href="{{ route('dashboard').'?from='.(new \Carbon\Carbon)->startOfWeek()->format('Y-m-d').'&to='.(new \Carbon\Carbon)->endOfWeek()->format('Y-m-d').'&interval=week' }}">This Week</a>
</div>
<div class="text-xl font-semibold">Venues</div>
<ul>
  @foreach ($venues as $venue)
    <li>
      <a href="{{ route('dashboard').'?venue='.$venue->id }}">
        {{ $venue->name }}
      </a>
      @if ($venue->rooms)
        <ul class="pl-4">
          @foreach ($venue->rooms as $room)
            <li>
              <a href="{{ route('dashboard').'?room='.$room->id }}">
                {{ $room->name }}
              </a>
            </li>
          @endforeach
        </ul>
      @endif
    </li>
  @endforeach
</ul>
