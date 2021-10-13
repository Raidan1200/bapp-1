<x-app-layout>
  @isset ($venue)
    <h1 class="px-4 text-2xl">Veranstaltungsort {{ $venue->name }} editieren</h1>
  @else
    <h1 class="px-4 text-2xl">Neuen Veranstaltungsort anlegen</h1>
  @endisset

  <form
    method="POST"
    action="{{ isset($venue) ? route('venues.update', $venue) : route('venues.store') }}"
    class="px-4"
  >
    @csrf
    @isset ($venue)
      @method('PUT')
    @endisset

    <x-auth-validation-errors></x-auth-validation-errors>

    <div class="sm:flex sm:space-x-8">
      <div class="w-full">
        <x-form-field>
          <x-label for="name">Name</x-label>
          <x-input type="text" name="name" class="w-full" value="{{ old('name') ?? $venue->name ?? '' }}" id="name" />
        </x-form-field>

        <x-form-field>
            <x-label for="slug">Slug</x-label>
            <x-input type="text" name="slug" class="w-full" value="{{ old('slug') ?? $venue->slug ?? '' }}" id="slug" />
          </x-form-field>

        <x-form-field>
          <x-label for="email">E-Mail</x-label>
          <x-input type="text" name="email" class="w-full" value="{{ old('email') ?? $venue->email ?? '' }}" id="email" />
        </x-form-field>

        <x-form-field>
            <x-label for="invoice_blocks">Rechnungs-Blöcke</x-label>
            <x-textarea rows=10 type="text" name="invoice_blocks" class="w-full" id="invoice_blocks" >{{ trim(json_encode(old('invoice_blocks') ?? $venue->invoice_blocks ?? '', JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), '"') }}</x-textarea>
          </x-form-field>

        <x-form-field>
          <x-label for="reminder_delay">Tage bis Erinnerungsmail</x-label>
          <x-input type="number" min="0" name="reminder_delay" class="w-full" value="{{ old('reminder_delay') ?? $venue->reminder_delay ?? '' }}"  id="reminder_delay" />
        </x-form-field>

        <x-form-field>
          <x-label for="check_delay">Tage bis Mitarbeiternotitz</x-label>
          <x-input type="number" min="0" name="check_delay" class="w-full" value="{{ old('check_delay') ?? $venue->check_delay ?? '' }}"  id="check_delay" />
        </x-form-field>

        <x-form-field>
          <x-label for="cancel_delay">Tage bis Stornierung</x-label>
          <x-input type="number" min="0" name="cancel_delay" class="w-full" value="{{ old('cancel_delay') ?? $venue->cancel_delay ?? '' }}"  id="cancel_delay" />
        </x-form-field>

        <x-form-field>
          <x-label for="invoice_id_format">Rechnungsnummernformat</x-label>
          <x-input type="text" name="invoice_id_format" class="w-full" value="{{ old('invoice_id_format') ?? $venue->invoice_id_format ?? '' }}" id="invoice_id_format" />
          </x-form-field>

        <div class="mt-2 text-right">
          <a href="{{ route('venues.index') }}">
            <x-button type="button">Cancel</x-button>
          </a>
          <x-button>Save</x-button>
        </div>

        @can('delete venues')
        {{-- TODO TODO: Löschen nur für Super Mega Hyper Universal ADMIN??? --}}
          <div class="sm:text-right mt-24">
            <x-button
              type="button"
              class="hover:bg-red-500"
            >
              <div
                x-data
                @click.prevent="$dispatch('open-delete-modal', {
                  route: '{{ route('venues.destroy', $venue) }}',
                  entity: '{{ $venue->name }}',
                  subText: '',
                })"
              >
                Veranstaltungsort löschen
              </div>
            </x-button>
          </div>
        @endcan
      </div>
    </form>
  </div>
</x-app-layout>
