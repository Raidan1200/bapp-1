<x-app-layout>
	<div class="flex">
    <div class="w-1/5">
      @include('dashboard.sidebar')
    </div>
    <div class="w-4/5">
      @include('dashboard.main')
    </div>
	</div>
</x-app-layout>
