<x-app-layout>
	<x-slot name="header">
    <div class="flex justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create Venue
      </h2>
    </div>
    {{-- TODO: Shouldn't this permission be checked at the controller level? --}}
    @can('create venues')
      <form
        method="POST"
        action="{{ isset($venue) ? route('venues.update', $venue->id) : route('venues.store') }}"
        class="px-4 py-4"
      >
        @csrf
        @if (isset($venue))
          @method('PUT')
        @endif

        <x-auth-validation-errors></x-auth-validation-errors>

        <x-label>Venue Name</x-label>
        <x-input type="text" name="name" class="w-full" value="{{ isset($venue) ? $venue->name : old('name') }}" />
        <div class="mt-2 text-right">
          <a href="{{ route('venues.index') }}">
            <x-button type="button">Cancel</x-button>
          </a>
          @if (isset($venue))
            <x-button>Save</x-button>
          @else
            <x-button>Update</x-button>
          @endif
        </div>
      </form>
    @endcan
  </div>
	</x-slot>
</x-app-layout>
