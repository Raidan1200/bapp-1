<x-app-layout>
  @livewireStyles

	<div class="flex">
    <div class="w-1/5">
      @include('dashboard.sidebar')
    </div>
    <div class="w-4/5">
      @include('dashboard.main')
    </div>
	</div>

  @livewireScripts
</x-app-layout>
