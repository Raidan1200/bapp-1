<div class="text-xl font-semibold">
  <a href="{{ route('dashboard', array_filter(array_merge($filters, [
      'from' => (new \Carbon\Carbon)->format('Y-m-d'),
      'days' => '1',
    ]))) }}"
  >
    Heute
  </a>
</div>
<div class="text-xl font-semibold">
  <a href="{{ route('dashboard', array_filter(array_merge($filters, [
      'from' => (new \Carbon\Carbon)->startOfWeek()->format('Y-m-d'),
      'days' => '7'
    ]))) }}"
  >
    Diese Woche
  </a>
</div>
