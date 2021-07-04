<x-app-layout>
	<x-slot name="header">
    <div class="flex justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Users
      </h2>
      @can('create users')
        <a href="{{ route('users.create') }}">
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
                <th scope="col" class="w-2/5 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Users
                </th>
                <th scope="col" class="w-2/5 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Venues
                </th>
                @canany(['modify users', 'delete users'])
                  <th scope="col" class="w-1/5 px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
              @endcanany
              </tr>
            </thead>
            @foreach ($users as $user)
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <a href="{{ route('users.show', $user->id) }}">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                          <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60" alt="">
                      </div>
                      <div class="ml-4">
                        <div class="font-medium text-gray-900">
                          {{ $user->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                          {{ ucfirst($user->roles->first()->name) }}
                        </div>
                      </div>
                    </div>
                  </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @forelse ($user->venues as $venue)
                    <ul>
                      <li>{{ $venue->name }}</li>
                    </ul>
                  @empty
                    No associated venues
                  @endforelse
                </td>
                @canany(['modify users', 'delete users'])
                  <td class="px-6 py-4 text-center text-sm font-medium">
                    <a class="inline-block" href="{{ route('users.edit', $user) }}">
                      <x-icons.edit class="h-4 w-4" />
                    </a>
                    <form class="inline-block" action="{{ route('users.destroy', $user) }}" method="POST">
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