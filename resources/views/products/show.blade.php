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
          <form class="inline-block" action="{{ route('products.destroy', $product) }}" method="POST">
            @method('delete')
            @csrf
            <button href="#">
              <x-icons.delete class="text-gray-600 h-6 w-6 hover:text-red-600" />
            </button>
          </form>
        @endcan
      </div>
    </div>
	</x-slot>

  {{-- Application UI > Lists > Tables > With avatars and multi-line content  --}}
	<div class="flex flex-col">
    <div class="px-4 m-2">
      <h3 class="font-semibold text-xl">Excerpt</h3>
      {{ $product->excerpt }}
    </div>
    <div class="p-4 m-2">
      <h3 class="font-semibold text-xl">Description</h3>
      {{ $product->description }}
    </div>
	</div>
</x-app-layout>