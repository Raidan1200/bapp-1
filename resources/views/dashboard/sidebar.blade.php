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
    <div class="text-xl font-semibold border-t-2 mt-2 pt-2">Springe zu</div>
    <form action="?" method="GET">
      <x-input type="date" name="from" id="" value="{{ $filters['from'] ?? '' }}" class="w-full" />
      <div class="my-2 flex justify-between">
        <x-button class="flex-1 mr-2" type="submit" name="days" value="1">Tag</x-button>
        <x-button class="flex-1" type="submit" name="days" value="7">Woche</x-button>
      </div>
    </form>
  </div>
  <div class="border-t-2 my-2 py-2">
    <div class="text-xl">Kundensuche</div>
    <livewire:customer-search />
  </div>
  <div class="border-t-2 my-2 py-2">
    <div class="text-xl">
      Checks
    </div>
    @can ('modify orders')
      @if ($paymentChecks->isNotEmpty())
        <ul>
          @foreach ($paymentChecks as $venue)
            <li>
              <a href="{{ route('dashboard', array_filter([
                'check' => true,
                'venue' => $venue->id
              ])) }}">
                {{ $venue->name }}: {{ $venue->check_count }}
              </a>
            </li>
          @endforeach
        </ul>
      @else
        <p>Heute keine Checks</p>
      @endif
    @endcan
    <div class="border-t-2 my-2 py-2">
      {{-- TODO: I guess the View shouldn't fire DB queries --}}
      <div class="text-xl">
        Neu
        <span class="text-xs">(seit gestern morgen)</span>
      </div>
      @if ($newOrderCount > 0)
        <a href="{{ route('dashboard', array_filter([
          'new' => true,
        ])) }}">
          {{ $newOrderCount }} Anfragen
        </a>
      @else
        <p>Keine neuen Bestellungen</p>
      @endif
    </div>
  </div>
</div>
