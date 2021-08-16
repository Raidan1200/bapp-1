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

<div class="mt-6">
  <span class="text-xl">This is just a test</span>
  <div class=" font-semibold flex flex-col">
    <a href="{{ route('dashboard', ['state' => 'fresh'])}}">Fresh</a>
    <a href="{{ route('dashboard', ['state' => 'deposit_paid'])}}">Deposit</a>
    <a href="{{ route('dashboard', ['state' => 'interim_paid'])}}">Interim</a>
    <a href="{{ route('dashboard', ['state' => 'final_paid'])}}">Final</a>
    <a href="{{ route('dashboard', ['state' => 'cancelled'])}}">Cancelled</a>
  </div>
</div>