<form>
  <div class="my-4">
    <h3 class="text-xl">Zugeordnete Produkte</h3>
    @if ($assignedPackages->count())
      <ul>
        @foreach ($assignedPackages as $package)
          <li class="flex justify-between">
            <div>{{ $package->name }}</div>
            <button type="button" wire:click="remove({{ $package }})">
              <x-icons.delete class="h-4" />
            </button>
          </li>
        @endforeach
      </ul>
    @else
      <div>Es wurden noch keine Produkte zugeordnet</div>
    @endif
  </div>

  <div class="my-4">
    <h3 class="text-xl">Nicht Zugeordnet</h3>
    @if ($otherPackages->count())
      <ul>
        @foreach ($otherPackages as $package)
          <li class="flex justify-between">
            <div>{{ $package->name }}</div>
            <button type="button" wire:click="add({{ $package }})">
              <x-icons.add class="h-4" />
            </button>
          </li>
        @endforeach
      </ul>
    @else
      <div>Es wurden alle Produkte zugeordnet</div>
    @endif
  </div>
</form>
