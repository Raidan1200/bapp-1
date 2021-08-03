
@isset ($from)
  <div class="flex justify-center">
    <div>
      <a href="{{ route('dashboard', [
        'from' => (new \Carbon\Carbon($from))->subDays($days)->format('Y-m-d'),
        'days' => $days
      ]) }}"
      >
        <
      </a>

      <span>
        {{ $from->format('d.m') }}
        @if ($days != 1)
          - {{ (new \Carbon\Carbon($from))->addDays($days)->subSeconds(1)->format('d.m') }}
        @endif
      </span>

      <a href="{{ route('dashboard', [
        'from' => (new \Carbon\Carbon($from))->addDays($days)->format('Y-m-d'),
        'days' => $days
      ]) }}"
      >
        >
      </a>
    </div>
  </div>
@endisset

<ul>
  @foreach ($orders as $order)
    <x-order :order="$order" />
  @endforeach
</ul>
{{ $orders->links() }}