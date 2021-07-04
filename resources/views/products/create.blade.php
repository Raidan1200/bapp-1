<x-app-layout>
	<x-slot name="header">
    <div class="flex justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create Product
      </h2>
    </div>
    {{-- TODO: Shouldn't this permission be checked at the controller level? --}}
    @can('create products')
      <form
        method="POST"
        action="{{ isset($product) ? route('products.update', $product) : route('products.store', $venue) }}"
        class="px-4 py-4"
        enctype="multipart/form-data"
      >
        @csrf
        @if (isset($product))
          @method('PUT')
        @endif

        <x-auth-validation-errors></x-auth-validation-errors>

        <x-label>Product Name</x-label>
        <x-input type="text" name="name" class="w-full" value="{{ isset($product) ? $product->name : old('name') }}" />

        <x-label class="mt-4">Excerpt</x-label>
        <x-textarea rows="2" name="excerpt" class="w-full" >{{ isset($product) ? $product->excerpt : old('excerpt') }}</x-textarea>

        <x-label class="mt-4">Description</x-label>
        <x-textarea rows="3" name="description" class="w-full" >{{ isset($product) ? $product->description : old('description') }}</x-textarea>

        <x-label class="mt-4">Capacity</x-label>
        <x-input type="number" name="capacity" class="w-full" value="{{ isset($product) ? $product->capacity : old('capacity') }}" />

        <div class="flex w-full mt-4">
          <div class="w-full mr-8">
            <x-label class="mt-4">Opening Time</x-label>
            <x-input type="number" min="0" max="23" name="opens_at" class="w-full" value="{{ isset($product) ? $product->opens_at : old('opens_at') }}" />
          </div>
          <div class="w-full">
            <x-label class="mt-4">Closing Time</x-label>
            <x-input type="number" min="0" max="23" name="closes_at" class="w-full" value="{{ isset($product) ? $product->closes_at : old('closes_at') }}" />
          </div>
        </div>

        <div class="flex w-full mt-4">
          <div class="w-full mr-8">
            <x-label>Price</x-label>
            <x-input type="text" name="price" class="w-full" value="{{ isset($product) ? $product->price : old('price') }}" />
          </div>
          <div class="w-full">
            <x-label>Deposit (%)</x-label>
            <x-input type="number" name="deposit" class="w-full" value="{{ isset($product) ? $product->deposit : old('deposit') }}" />
          </div>
        </div>

        <x-label class="mt-4">Image</x-label>
        @if (isset($product))
          <img src="{{ $product->image }}" alt="{{ $product->title }} product image">
        @endif

        <x-input type="file" name="image" class="w-full" />

        <div class="mt-2 text-right">
          <a href="{{ isset($product) ? route('products.show', $product) : route('venues.show', $venue) }}">
            <x-button type="button">Cancel</x-button>
          </a>
          <x-button>Save</x-button>
        </div>
      </form>
    @endcan
  </div>
	</x-slot>
</x-app-layout>
