<div>
  <form
    wire:submit.prevent="save"
    action="#"
  >
    <select
      wire:model="selectedStatus"
      class="p-0"
      name="order-status"
      id="order-status"
    >
      <option value="deposit_mail_sent">Unbestätigt</option>
      <option value="deposit_paid">Bestätigt</option>
      <option value="intermed_paid">Anzahlungs-E-Mail versendet</option>
      <option value="intermed_mail_sent">Anzahlung eingegangen</option>
      <option value="final_mail_sent">Gesamtrechnungs-E-Mail versendet</option>
      <option value="final_paid">Gesamtrechnung bezahlt</option>
    </select>
    @if ($dirty)
      <button class="bg-green-300 px-2 py-1 rounded-xl">Save</button>
      <button wire:click.prevent="nope" class="bg-green-300 px-2 py-1 rounded-xl">Cancel</button>
    @endif
    <button></button>
  </form>
</div>
