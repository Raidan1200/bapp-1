<div>
  <x-input class="w-full" type="text" wire:model="customerName" />
  <ul class="m-2">
    @foreach ($customers as $customer)
      <li class="mb-2">
        <a href="{{ route('customers.show', $customer) }}">
          {{$customer->name }}
          @if ($customer->company)
            <div class="ml-2 text-gray-700">{{ $customer->company }}</div>
          @endif
        </a>
      </li>
    @endforeach
  </ul>
</div>
