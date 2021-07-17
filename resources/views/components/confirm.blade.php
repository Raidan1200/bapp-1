<div x-cloak>
  <div
  style="background-color: rgba(0, 0, 0, 0.8); display: none"
  class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full"
  x-show="isOpen"
  x-transition:enter="ease-out transition-slow"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="ease-in transition-slow"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  >
  <div
    class="p-4 max-w-xl mx-auto absolute left-0 right-0 overflow-hidden mt-24 bg-white rounded-xl"
    x-on:click.away="isOpen = false"
  >
    <form
    method="POST"
    x-bind:action="route"
    >
    @method('DELETE')
    @csrf

    <div>
      Are you sure you want to delete <span x-text="entityName"></span>?
    </div>
    <div>
      Once deleted, this can not be undone.
    </div>

    <div class="text-right">
      <button
      type="button"
      x-on:click="isOpen = false"
      class="px-4 py-2 rounded-lg text-gray-600 bg-white hover:text-blue-600 shadow mr-2"
      >Cancel</button>
      <button
      type="submit"
      name="deleteButton"
      class="px-4 py-2 rounded-lg text-white bg-red-500 hover:bg-red-600 shadow"
      >Confirm</button>
    </div>
    </form>
  </div>
  </div>
</div>
