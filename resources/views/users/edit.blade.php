<x-app-layout>
	<x-slot name="header">
    <div class="flex justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit User
      </h2>
    </div>
    <div class="flex space-x-4">
      <form
        method="POST"
        action="{{ route('users.update', $user) }}"
        class="md:w-1/2 flex flex-col space-y-4"
        enctype="multipart/form-data"
      >
        @csrf
        @if (isset($user))
          @method('PUT')
        @endif

        <x-auth-validation-errors></x-auth-validation-errors>
        <div>
          <x-label>User Name</x-label>
          <x-input type="text" name="name" class="w-full" value="{{ $user->name }}" />
        </div>

        <div>
          <x-label>E-Mail</x-label>
          <x-input type="text" name="email" class="w-full" value="{{ $user->email }}" />
        </div>

        <div>
          <x-label>Role</x-label>
          <select name="role" id="role" class="w-full">
            @foreach ($roles as $role)
              <option
                value="{{ $role->id }}"
                {{ (isset($user) && $role->id === $user->roles->first()->id) ? 'selected' : '' }}
              >{{ ucfirst($role->name) }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <x-label>New Password</x-label>
          <x-input type="text" name="password" class="w-full" />
        </div>

        <div>
          <x-label>Confirm Password</x-label>
          <x-input type="text" name="password_confirmation" class="w-full" />
        </div>

        @if (isset($user->image))
          <div class="mt-4">
            <x-label>Profile Image</x-label>
            <img class="p-4" src="{{ $user->image }}" alt="{{ $user->name }} profile image">
          </div>
        @endif

        <div class="mt-2 text-right">
          <a href="{{ route('users.index') }}">
            <x-button type="button">Cancel</x-button>
          </a>
          <x-button>Save</x-button>
        </div>
      </form>

      <div class="md:w-1/2 mt-8 md:mt-0">
        <h3 class="text-lg font-semibold">Venues</h3>
        @forelse ($user->venues as $venue)
          <ul>
            <li class="flex justify-between">
              <span>{{ $venue->name }}</span>
              <button
                x-data
                x-on:click="
                  $dispatch('open-delete-modal', {
                    route: '{{ route('venuemember.destroy', ['venue' => $venue, 'user' => $user]) }}',
                    entityName: '{{ $user->name }} from {{ $venue->name }}'
                  })
                "
              >
                <x-icons.delete class="h-4 w-4 hover:text-red-600" />
              </button>
            </li>
          </ul>
        @empty
          No associated venues.
        @endforelse
        <div class="mt-6">
          <form action="{{ route('venuemember.store', $user) }}" method="POST">
            @csrf
            <select name="venue" id="venue">
              <option value="">-- Select a venue --</option>
              @foreach ($venues as $venue)
                <option value="{{ $venue->id }}">{{ $venue->name }}</option>
              @endforeach
            </select>
            <button>Add {{ $user->name }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
	</x-slot>
</x-app-layout>
