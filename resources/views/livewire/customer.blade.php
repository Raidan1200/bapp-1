<div>
  @if ($editing)
    <form
      wire:submit.prevent="save"
      action="#"
      class="px-4"
    >
      <div>
        <div class="sm:flex sm:justify-between sm:space-x-4">
          <div class="sm:w-1/2">
            <x-form-field>
              <x-label for="first_name">Vorname</x-label>
              <x-input class="w-full px-2" wire:model="customer.first_name" id="first_name" />
            </x-form-field>
            <x-form-field>
              <x-label for="last_name">Nachname</x-label>
              <x-input class="w-full px-2" wire:model="customer.last_name" id="last_name" />
            </x-form-field>

            <x-form-field>
              <x-label for="street">Straße</x-label>
              <x-input class="w-full px-2" wire:model="customer.street" id="street" />
            </x-form-field>
            <x-form-field>
              <x-label for="street_no">Nr.</x-label>
              <x-input class="w-full px-2" wire:model="customer.street_no" id="street_no" />
            </x-form-field>

            <x-form-field>
              <x-label for="zip">PLZ</x-label>
              <x-input class="w-full px-2" wire:model="customer.zip" id="zip" />
            </x-form-field>
            <x-form-field>
              <x-label for="city">Stadt</x-label>
              <x-input class="w-full px-2" wire:model="customer.city" id="city" />
            </x-form-field>
          </div>
          <div class="sm:w-1/2 sm:flex sm:flex-col sm:justify-between">
            <div class="">
              <x-form-field>
                <x-label for="company">Firma</x-label>
                <x-input class="w-full px-2" wire:model="customer.company" id="company" />
              </x-form-field>
              <x-form-field>
                <x-label for="phone">Telefon</x-label>
                <x-input class="w-full px-2" wire:model="customer.phone" id="phone" />
              </x-form-field>
              <x-form-field>
                <x-label for="email">E-Mail</x-label>
                <x-input class="w-full px-2" wire:model="customer.email" id="email" />
              </x-form-field>
            </div>
            <div class="pb-4 text-right">
              <x-button>Speichern</x-button>
              <x-button class="button" wire:click="cancel">Abbrechen</x-button>
            </div>
          </div>
        </div>
      </div>
    </form>
  @else
    <div>
      @can('admin orders')
        <button
          class="float-right p-2"
          wire:click="startEditing"
        >
          <x-icons.edit />
        </button>
      @endcan
      @if ($customer->company)
        <div>Firma: {{ $customer->company }}</div>
      @endif
      <div>{{ $customer->street }} {{ $customer->street_no }}</div>
      <div>{{ $customer->zip }} {{ $customer->city }}</div>
      <div>Tel: {{ $customer->phone }}</div>
      <div>E-Mail:
        <x-link href="mailto:{{ $customer->email}}">{{ $customer->email }}</x-link>
      </div>
    </div>
  @endif
</div>