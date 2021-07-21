
@isset ($from)
  <div class="flex justify-center">
    <div>
      @if ($interval === 'week')
        <a href="{{ route('dashboard', ['from' => \Carbon\Carbon::parse($from)->subWeek()->format('Y-m-d'), 'to' => \Carbon\Carbon::parse($to)->subWeek()->format('Y-m-d')]).'&interval=week' }}"><</a>
      @else
        <a href="{{ route('dashboard', ['from' => \Carbon\Carbon::parse($from)->subday()->format('Y-m-d'), 'to' => \Carbon\Carbon::parse($to)->subday()->format('Y-m-d')]) }}"><</a>
      @endif

      <span>
        {{ $from->format('d.m') }}
        @if ($from->notEqualTo($to))
          - {{ $to->format('d.m') }}
        @endif
      </span>

      @if ($interval === 'week')
        <a href="{{ route('dashboard', ['from' => \Carbon\Carbon::parse($from)->addWeek()->format('Y-m-d'), 'to' => \Carbon\Carbon::parse($to)->addWeek()->format('Y-m-d')]).'&interval=week' }}">></a>
      @else
        <a href="{{ route('dashboard', ['from' => \Carbon\Carbon::parse($from)->addDay()->format('Y-m-d'), 'to' => \Carbon\Carbon::parse($to)->addDay()->format('Y-m-d')]) }}">></a>
      @endif
    </div>
  </div>
@endisset

<ul>
  @foreach ($orders as $order)
    <x-order :order="$order" />
  @endforeach
</ul>
