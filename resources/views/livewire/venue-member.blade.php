<form>
  <x-label>Veranstaltungsorte</x-label>
  <div class="my-4">
    <h3 class="text-xl">Zugeordnet</h3>
    @if ($user->venues->count())
      <ul class="pl-2">
        @foreach ($user->venues as $venue)
          <li class="flex justify-between">
            <div>{{ $venue->name }}</div>
            @can('modify users')
              <button type="button" wire:click="remove({{ $venue }})">
                <x-icons.delete class="h-4" />
              </button>
            @endcan
          </li>
        @endforeach
      </ul>
    @else
    <div class="pl-2">
      Keine
    </div>
    @endif
  </div>

  <h3 class="text-xl">Nicht Zugeordnet</h3>
    @if ($venues->count())
    <ul class="pl-2">
      @foreach ($venues as $venue)
        <li class="flex justify-between">
          <div>{{ $venue->name }}</div>
          @can('modify users')
            <button type="button" wire:click="add({{ $venue }})">
              <x-icons.add class="h-4" />
            </button>
          @endcan
        </li>
      @endforeach
    </ul>
  @else
  <div class="pl-2">
    Keine
  </div>
  @endif
</form>
