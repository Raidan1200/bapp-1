<div class="text-xl font-semibold">
  <a href="{{ route('dashboard', [
      'from' => (new \Carbon\Carbon)->format('Y-m-d'),
      'days' => '1'
    ]) }}"
  >
    Heute
  </a>
</div>
<div class="text-xl font-semibold">
  <a href="{{ route('dashboard', [
      'from' => (new \Carbon\Carbon)->startOfWeek()->format('Y-m-d'),
      'days' => '7'
    ]) }}"
  >
    Diese Woche
  </a>
</div>

<div class="mt-4 text-xl font-semibold">Orte</div>
<ul>
  @foreach ($venues as $venue)
    <li>
      <a href="{{ route('dashboard', ['venue' => $venue->id]) }}">
        {{ $venue->name }}
      </a>
      @if ($venue->rooms)
        <ul class="pl-4">
          @foreach ($venue->rooms as $room)
            <li>
              <a href="{{ route('dashboard', ['room' => $room->id]) }}">
                {{ $room->name }}
              </a>
            </li>
          @endforeach
        </ul>
      @endif
    </li>
  @endforeach
</ul>
