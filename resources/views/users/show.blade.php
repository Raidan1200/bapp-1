<x-app-layout>
	<x-slot name="header">
    <div class="flex justify-between">
      <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          <a href="{{ route('users.index') }}">Users</a>
          >
          {{ $user->name }}
        </h2>
      </div>
      <div class="flex space-x-2">
        @can('modify users')
          <a href="{{ route('users.edit', $user) }}">
            <x-icons.edit class="text-gray-600 h-6 w-6 hover:text-gray-900" />
          </a>
        @endcan
        @can('delete users')
          <form class="inline-block" action="{{ route('users.destroy', $user) }}" method="POST">
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

	<div class="flex flex-col">
    <div class="px-4 m-2">
      <p>User name: {{ $user->name }}</p>
      <p>E-Mail: {{ $user->email }}</p>
      @if ($user->image)
        <img src="{{ $user->image }}" alt="{{ $user->name }} profile image">
      @else
        No profile image available.
      @endif
    </div>
	</div>
</x-app-layout>