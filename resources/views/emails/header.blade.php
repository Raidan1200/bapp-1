<div class="flex bg-gray-200">
  <div class="w-3/5">
    <div>
      {{ $venue->invoice_blocks['company'] }} *
      {{ $venue->invoice_blocks['street'] }} {{ $venue->invoice_blocks['street_no'] }} *
      {{ $venue->invoice_blocks['zip'] }} {{ $venue->invoice_blocks['city'] }}
    </div>
    <div>
      @if ($customer->company)
        <div>({{ $customer->company }})</div>
      @endif
      <div>{{ $customer->name }}</div>
      <div>{{ $customer->street }} {{ $customer->street_no }}</div>
      <div>{{ $customer->zip }} {{ $customer->city }}</div>
    </div>
  </div>
  <div class="w-2/5">
    <div>
      <img src="{{ public_path() . '/images/' . $venue->slug . '_logo.svg' }}" alt="">
    </div>
    <table>
      <tr>
        <td>Telefon:</td>
        <td>{{ $venue->invoice_blocks['phone'] }}</td>
      </tr>
      <tr>
        <td>Fax:</td>
        <td>{{ $venue->invoice_blocks['fax'] }}</td>
      </tr>
      <tr>
        <td>E-Mail:</td>
        <td>{{ $venue->invoice_blocks['email'] }}</td>
      </tr>
      <tr>
        <td>Internet:</td>
        <td>{{ $venue->invoice_blocks['web'] }}</td>
      </tr>
      <tr>
        <td colspan="2">{{ $venue->invoice_blocks['bank'] }}</td>
      </tr>
      <tr>
        <td>IBAN:</td>
        <td>{{ $venue->invoice_blocks['iban'] }}</td>
      </tr>
      <tr>
        <td>BIC:</td>
        <td>{{ $venue->invoice_blocks['bic'] }}</td>
      </tr>
    </table>
  </div>
</div>
