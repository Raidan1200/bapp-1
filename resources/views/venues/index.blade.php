<x-app-layout>
	<x-slot name="header">
    <div class="flex justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Venues
      </h2>
      @can('create venues')
        <a href="{{ route('venues.create') }}">
          <x-icons.add class="h-6 w-6" />
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
                  Venue
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Users
                </th>
                @canany(['modify products', 'delete products'])
                  <th scope="col" class="w-1/5 px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
              @endcanany
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach ($venues as $venue)
              {{-- Venue --}}
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <a href="{{ route('venues.show', $venue) }}">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                          <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60" alt="">
                      </div>
                      <div class="ml-4">
                        <div class="font-medium text-gray-900">
                          {{ $venue->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                          {{ $venue->products->count() }} Products
                        </div>
                      </div>
                    </div>
                  </a>
                </td>
                {{-- Users --}}
                <td class="px-6 py-4 whitespace-nowrap">
                  @forelse ($venue->users as $user)
                    <ul>
                      <li class="text-sm">{{ $user->name }}</li>
                    </ul>
                  @empty
                    No associated users
                  @endforelse
                </td>
                {{-- Actions --}}
                @canany(['modify venues', 'delete venues'])
                  <td class="px-6 py-4 text-center text-sm font-medium">
                    @can('modify venues')
                      <a class="inline-block" href="{{ route('venues.edit', $venue) }}">
                        <x-icons.edit class="h-4 w-4" />
                      </a>
                    @endcan
                    @can('delete venues')
                      <button
                        x-data
                        x-on:click="
                          $dispatch('open-delete-modal', {
                            route: '{{ route('venues.destroy', $venue) }}',
                            entityName: '{{ $venue->name }}'
                          })
                        "
                      >
                        <x-icons.delete class="h-4 w-4 hover:text-red-600" />
                      </button>
                    @endcan
                  </td>
                @endcanany
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
	</div>
</x-app-layout>