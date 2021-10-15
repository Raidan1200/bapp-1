<div>
  <form
    wire:submit.prevent="save"
    enctype="multipart/form-data"
  >
    <x-label for="image">Profilbild</x-label>

    @if ($user->image)
      <img src="{{ Storage::url($user->image) }}" alt="">
    @else
      <div class="p-4">
        Es wurde noch kein Profilbild hochgeladen
      </div>
    @endif

    <input type="file" wire:model="image" id="{{ $idHack }}">

    @error('image')
      <div class="text-red-500">
        {{ $message }}
      </div>
    @enderror

    <div class="py-4 text-right">
      <x-button type="submit">Bild hochladen</x-button>
      @if ($user->image)
        <x-button wire:click="delete" type="button">Bild löschen</x-button>
      @endif
    </div>
  </form>
</div>
