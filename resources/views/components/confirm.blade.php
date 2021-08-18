<div x-cloak>
  <div
    style="background-color: rgba(0, 0, 0, 0.8); display: none"
    class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full"
    x-show="open"
    x-transition:enter="ease-out transition-slow"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in transition-slow"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
  >
  <div
    class="p-4 max-w-xl mx-auto absolute left-0 right-0 overflow-hidden mt-24 bg-white rounded-xl"
    @click.away="open = false"
  >
    <form
      method="POST"
      x-bind:action="route"
    >
      @method('DELETE')
      @csrf
      <h3 class="text-2xl">Achtung!</h3>

      <div class="my-4">
        Sind Sie sicher, dass sie <span class="underline font-semibold" x-text="entity"></span> löschen wollen?
      </div>
      <div class="my-4">
        Diese Aktion kann nicht rückgängig gemacht werden.
      </div>

      <div class="text-right">
        <button
          type="button"
          x-on:click="open = false"
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
