<footer class="absolute bottom--32 flex w-full h-24 border-t pt-4 mt-4 bg-white">
  <div class="w-1/3 h-24">
    <div>{{ $venue->invoice_blocks['company'] }}</div>
    <div>Geschäftsführer</div>
    <div>{{ $venue->invoice_blocks['manager'] }}</div>
  </div>
  <div class="w-1/3">
    <div>{{ $venue->invoice_blocks['tax_id'] }}</div>
    <div>{{ $venue->invoice_blocks['hrb'] }}</div>
    <div>{{ $venue->invoice_blocks['court'] }}</div>
  </div>
  <div class="w-1/3">
    <div>Firmensitz {{ $venue->invoice_blocks['city'] }}</div>
    <div>{{ $venue->invoice_blocks['street'] }} {{ $venue->invoice_blocks['street_no'] }}</div>
    <div>{{ $venue->invoice_blocks['zip'] }} {{ $venue->invoice_blocks['city'] }}</div>
  </div>
</footer>
