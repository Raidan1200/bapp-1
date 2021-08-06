<x-app-layout>
	<div class="lg:flex">
    <div class="lg:w-1/5">
      @include('dashboard.sidebar')
    </div>
    <div class="lg:w-4/5">
      @include('dashboard.main')
    </div>
	</div>
</x-app-layout>
