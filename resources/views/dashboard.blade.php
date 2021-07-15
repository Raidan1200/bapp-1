<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			{{ __('Dashboard') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
			<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        @foreach ($orders as $order)
          <article class="p-2 m-2" x-data="{ showBookings: false }">
            {{-- Heading --}}
            <div @click="showBookings = ! showBookings" class="flex justify-between space-x-4 mb-2 hover:bg-gray-100">
              <h2 class="text-xl">#{{ $order->id }} : {{ $order->customer['name'] }} booked {{ $order->bookings[0]->product->venue->name }}</h2>
              <div class="text-xl">{{ $order->bookings[0]->starts_at->toDateString() }}</div>
            </div>
            {{-- Order Info --}}
            <div class="flex justify-between space-x-4 mb-4">
              <div class="w-1/2">
                <div class="">{{ $order->customer['address'] }}</div>
                <div class="">Phone: {{ $order->customer['phone'] }}</div>
              </div>
              <div class="w-1/2 text-right">
                <div class="">Status: {{ $order->status }}</div>
                <div class="">Cash Payment: {{ $order->cash_payment ? 'yes' : 'no' }}</div>
              </div>
            </div>
            {{-- Bookings --}}
            <table x-show="showBookings" class="table-fixed min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="w-2/6 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Product
                  </th>
                  <th scope="col" class="w-1/6 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Start
                  </th>
                  <th scope="col" class="w-1/6 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    End
                  </th>
                  <th scope="col" class="w-1/6 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Quantity
                  </th>
                  @canany(['modify users', 'delete users'])
                    <th scope="col" class="w-1/6 px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  @endcanany
                </tr>
              </thead>
              @forelse ($order->bookings as $booking)
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr>
                    {{-- Product --}}
                    <td class="px-4 py-2 whitespace-nowrap">
                      <div class="font-medium text-gray-900">
                        {{ $booking->product->name }}
                      </div>
                    </td>
                    {{-- Start & End --}}
                    <td class="px-4 py-2 whitespace-nowrap">
                      <div class="font-medium text-gray-900">
                        {{ $booking->starts_at->format('H:i') }}
                      </div>
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap">
                      <div class="font-medium text-gray-900">
                        {{ $booking->ends_at->format('H:i') }}
                      </div>
                    </td>
                    {{-- Quantity --}}
                    <td class="px-4 py-2 whitespace-nowrap">
                      <div class="font-medium text-gray-900">
                        {{ $booking->quantity }}
                      </div>
                    </td>
                    {{-- Actions --}}
                    @canany(['modify users', 'delete users'])
                      <td class="px-4 py-2 text-center text-sm font-medium">
                        {{-- <a class="inline-block" href="{{ route('users.edit', $user) }}"> --}}
                          <x-icons.edit class="h-4 w-4" />
                        {{-- </a> --}}

                        {{-- <button
                          x-data
                          x-on:click="
                            $dispatch('open-delete-modal', {
                              route: '{{ route('users.destroy', $user) }}',
                              entityName: '{{ $user->name }}'
                            })
                          "
                        >
                          <x-icons.delete class="h-4 w-4 hover:text-red-600" />
                        </button> --}}
                      </td>
                    @endcanany
                  </tr>
                </tbody>
              @endforeach
            </table>
          </article>
        @endforeach
			</div>
		</div>
	</div>
</x-app-layout>
