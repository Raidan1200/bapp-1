<div class="flex justify-end mt-6">
  <table>
    <tr>
      <td class="font-semibold">Gesamt Netto:</td>
      <td class="text-right">{{ money($order->net_total) }} Euro</td>
    </tr>
    @foreach ($order->vats as $vat => $amount)
      <tr>
        <td>zzgl. {{ $vat }}% MwSt:</td>
        <td class="text-right">{{ money($amount) }} Euro</td>
      </tr>
    @endforeach
    <tr>
      <td class="font-semibold">Gesamt Brutto:</td>
      <td class="text-right">{{ money($order->gross_total) }} Euro</td>
    </tr>
  </table>
</div>
