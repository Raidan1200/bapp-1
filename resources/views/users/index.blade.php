<x-app-layout>
	<x-slot name="header">
    <div class="flex justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Users
      </h2>
      @can('create users')
        <a href="{{ route('users.create') }}" title="Create New User">
          <x-icons.add class="h-6 w-6" />
        </a>
      @endcan
    </div>
	</x-slot>

  <div class="flex flex-col">
    Users Index
  </div>
</x-app-layout>