<div>
  <x-input class="w-full" type="text" wire:model="customerName" />
  <ul>
    @foreach ($customers as $customer)
      <li>
        <a href="{{ route('customers.show', $customer) }}">{{$customer->name }}</a>
      </li>
    @endforeach
  </ul>
</div>
