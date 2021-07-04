<x-app-layout>
	<x-slot name="header">
    <div class="flex justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create User
      </h2>
    </div>
    {{-- TODO: Shouldn't this permission be checked at the controller level? --}}
    @can('create users')
      <form
        method="POST"
        action="{{ route('users.store') }}"
        class="px-4 py-4"
        enctype="multipart/form-data"
      >
        @csrf
        @if (isset($user))
          @method('PUT')
        @endif

        <x-auth-validation-errors></x-auth-validation-errors>

        <div class="flex flex-col space-y-4">

          <div>
            <x-label>User Name</x-label>
            <x-input type="text" name="name" class="w-full" value="{{ old('name') }}" />
          </div>

          <div>
            <x-label>E-Mail</x-label>
            <x-input type="text" name="email" class="w-full" value="{{ old('email') }}" />
          </div>

          <div>
            <x-label>Role</x-label>
            <select name="role" id="role" class="w-full">
              <option value="">-- Select a role --</option>
              @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
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
        </div>

        <div class="mt-2 text-right">
          <a href="{{ route('users.index') }}">
            <x-button type="button">Cancel</x-button>
          </a>
          <x-button>Save</x-button>
        </div>
      </form>
    @endcan
  </div>
	</x-slot>
</x-app-layout>
