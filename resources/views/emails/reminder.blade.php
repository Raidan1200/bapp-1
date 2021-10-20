<x-email-layout>
  <h1 class="text-2xl">Ihre Abschlussrechnung</h1>

  <p class="my-2">Wir freuen uns, dass Sie sich für {{ $order->venue->name }} entschieden haben.</p>

  <p class="my-2">
    Allerdings konnten wir für die noch ausstehende Anzahlung der Reservierungen bisher keinen Zahlungseingang feststellen.
    Bitte überweisen sie daher umgehend die Anzahlung von {{ money($order->deposit_amount) }}€,
    da sonst die reservierten Plätze wieder automatisch vom System freigegeben werden.
  </p>

  <p class="my-2">Vielen Dank für ihr Verständnis.</p>

</x-email-layout>
