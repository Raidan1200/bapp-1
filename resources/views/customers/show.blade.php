<x-app-layout>
	<div class="lg:flex">
    <div class="lg:w-1/5">
      @include('dashboard.sidebar')
    </div>
    <div class="lg:w-4/5">
      <div class="p-4">
        <livewire:customer :customer="$customer" />
      </div>
      @foreach ($orders as $order)
        <livewire:order :order="$order" />
      @endforeach
	</div>
</x-app-layout>
