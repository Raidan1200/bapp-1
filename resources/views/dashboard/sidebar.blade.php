<div class="flex flex-col">
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
  <div>
    <form action="?" method="GET">
      <input type="date" name="from" id="" value="{{ $filters['from'] ?? '' }}">
      <div>
        <x-button type="submit" name="days" value="1">Tag</x-button>
        <x-button type="submit" name="days" value="7">Woche</x-button>
      </div>
    </form>
  </div>
  <div class="border-t-2 my-2 py-2">
    <div class="text-xl">Kundensuche</div>
    <livewire:customer-search />
  </div>
</div>