<x-app-layout>
	<x-slot name="header">
    <div class="flex justify-between">
      <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          <a href="{{ route('venues.index') }}">Venues</a>
          >
          <a href="{{ route('venues.show', $product->venue) }}">
            {{ $product->venue->name }}
          </a>
          >
          {{ $product->name }}
        </h2>
      </div>
      <div class="flex space-x-2">
        @can('modify products')
          <a href="{{ route('products.edit', $product) }}">
            <x-icons.edit class="text-gray-600 h-6 w-6 hover:text-gray-900" />
          </a>
        @endcan
        @can('delete products')
          <button
            x-data
            x-on:click="
              $dispatch('open-delete-modal', {
                route: '{{ route('products.destroy', $product) }}',
                entityName: '{{ $product->name }}'
              })
            "
          >
            <x-icons.delete class="h-6 w-6 text-gray-600 hover:text-red-600" />
          </button>
        @endcan
      </div>
    </div>
	</x-slot>

  <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
    <div class="shadow border-b border-gray-200 bg-white sm:rounded-lg p-6">
      <div class="flex space-x-4">
        <div class="w-1/2">
          <div class="mb-4">
            <h3 class="font-semibold text-xl">Excerpt</h3>
            <div>{{ $product->excerpt }}</div>
          </div>
          <div class="mb-4">
            <h3 class="font-semibold text-xl">Description</h3>
            <div>{{ $product->description }}</div>
          </div>
        </div>
        <div class="w-1/2">
          <h3 class="font-semibold text-xl">Product Image</h3>
          @if ($product->image)
            <img src="{{ url($product->image) }}" alt="{{ $product->name }} product image">
          @else
            No image yet.
          @endif
        </div>
      </div>
      <div class="flex space-x-4">
        <div class="w-1/2">
          <div class="mb-4">
            <h3 class="font-semibold text-xl">Capacity</h3>
            <div>{{ $product->capacity }}</div>
          </div>
          <div class="mb-4">
            <h3 class="font-semibold text-xl">Price</h3>
            <div>{{ $product->price }}</div>
          </div>
          <div class="mb-4">
            <h3 class="font-semibold text-xl">Default Deposit</h3>
            <div>{{ $product->deposit }}</div>
          </div>
        </div>
        <div class="w-1/2">
          <div class="mb-4">
            <h3 class="font-semibold text-xl">Opening Hours</h3>
            <div>Opens at: {{ $product->opens_at }}</div>
          </div>
          <div class="mb-4">
            <h3 class="font-semibold text-xl">Description</h3>
            <div>Closes at: {{ $product->closes_at }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>