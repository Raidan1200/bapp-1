<form>
  <div class="my-4">
    <h3 class="text-xl">Zugeordnete Produkte</h3>
    @if ($assignedProducts->count())
      <ul>
        @foreach ($assignedProducts as $product)
          <li class="flex justify-between">
            <div>{{ $product->name }}</div>
            <button type="button" wire:click="remove({{ $product }})">
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
    @if ($otherProducts->count())
      <ul>
        @foreach ($otherProducts as $product)
          <li class="flex justify-between">
            <div>{{ $product->name }}</div>
            <button type="button" wire:click="add({{ $product }})">
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
