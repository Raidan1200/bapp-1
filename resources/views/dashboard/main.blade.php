
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
        <a href="{{ url()->full() }}">
          {{ $from->format('d.m') }}
          @if ($days != 1)
            - {{ (new \Carbon\Carbon($from))->addDays($days)->subSeconds(1)->format('d.m') }}
          @endif
        </a>
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

{{ $orders->links() }}

@foreach ($orders as $order)
  <livewire:order :order="$order" />
@endforeach

{{ $orders->links() }}
