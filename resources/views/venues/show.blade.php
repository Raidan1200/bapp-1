<x-app-layout>
	<x-slot name="header">
    <div class="flex justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <a href="{{ route('venues.index') }}">Venues</a>
         > {{ $venue->name }}
      </h2>
      @can('create venues')
        <a href="{{ route('products.create', $venue->id) }}">
          <x-icons.add class="w-6 h-6" />
        </a>
      @endcan
    </div>
	</x-slot>

  {{-- Application UI > Lists > Tables > With avatars and multi-line content  --}}
	<div class="flex flex-col">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow border-b border-gray-200 sm:rounded-lg">
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <table class="table-fixed min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Product
              </th>
              @canany(['modify products', 'delete products'])
                <th scope="col" class="w-1/5 px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
            @endcanany
            </tr>
          </thead>
          @foreach ($venue->products as $product)
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td class="px-6 py-4">
                  <a href="{{ route('products.show', $product) }}">
                    <div class="flex items-center">
                      <div class="h-10 w-10">
                        <img class="h-10 w-10 rounded-full" src="{{ url($product->image ?? '') }}" alt="">
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                          {{ $product->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                          {{ Str::limit($product->excerpt, 80) }}
                        </div>
                      </div>
                    </div>
                  </a>
                </td>
                @canany(['modify products', 'delete products'])
                  <td class="px-6 py-4 text-center text-sm font-medium">
                    <a class="inline-block" href="{{ route('products.edit', $product) }}">
                      <x-icons.edit class="h-4 w-4" />
                    </a>
                    <form class="inline-block" action="{{ route('products.destroy', $product) }}" method="POST">
                      @method('delete')
                      @csrf
                      <button href="#">
                        <x-icons.delete class="h-4 w-4 hover:text-red-600" />
                      </button>
                    </form>
                  </td>
                @endcanany
              </tr>
            </tbody>
          @endforeach
        </table>
      </div>
    </div>
	</div>
</x-app-layout>