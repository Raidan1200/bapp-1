@props(['status'])

@if ($status)
  <div
    {{ $attributes->merge(['class' => 'bg-green-300 p-4 sm:-mt-4 m-4 rounded-xl']) }}
    x-data="{ show: true }"
    x-show="show"
    x-init="() => {
      setTimeout(() => show = true, 300);
      setTimeout(() => show = false, 3000);
    }"
    @click.away="show = false"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
  >
    <div class="flex justify-between">
      <div>
        {{ $status }}
      </div>
      <div>
        <button @click="show = false" class="float-right focus:outline-none focus:text-gray-800 transition ease-in-out duration-150">
          X
        </button>
      </div>
    </div>
  </div>
@endif
