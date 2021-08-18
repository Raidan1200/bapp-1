@isset ($filters['from'])
  <div class="flex justify-center">
    <div>
      <a href="{{ route('dashboard', array_filter(array_merge($filters, [
        'from' => (new \Carbon\Carbon($filters['from']))->subDays($filters['days'])->format('Y-m-d'),
        'days' => $filters['days']
      ]))) }}"
      >
        <span class="inline-block mr-2">
          <x-icons.chevron-left />
        </span>
      </a>

      <div class="inline-block text-center">
        <div>KW: {{ (new \Carbon\Carbon($filters['from']))->week() }}</div>
        <div>
          <a href="{{ url()->full() }}">
            {{ (new \Carbon\Carbon($filters['from']))->format('d.m') }}
            @if ($filters['days'] != 1)
              - {{ (new \Carbon\Carbon($filters['from']))->addDays($filters['days'])->subSeconds(1)->format('d.m') }}
            @endif
          </a>
        </div>
      </div>

      <a href="{{ route('dashboard', array_filter(array_merge($filters, [
        'from' => (new \Carbon\Carbon($filters['from']))->addDays($filters['days'])->format('Y-m-d'),
        'days' => $filters['days']
      ]))) }}"
      >
        <span class="inline-block ml-2">
          <x-icons.chevron-right />
        </span>
      </a>
    </div>
  </div>
  <div>{{ $orders->links() }}</div>
@endisset


@foreach ($orders as $order)
  <livewire:order :order="$order" />
@endforeach

{{ $orders->links() }}
