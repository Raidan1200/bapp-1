<x-app-layout>
  <h1 class="text-2xl">Ihre Anzahlungsbestätigung</h1>

  <p class="my-2">Vielen Dank für ihr Buchung bei {{ $order->venue->name }}.</p>

  <p class="my-2">Ihre Anzahlung für die gebuchte Reservierung haben wir erhalten und bestätigen hiermit verbindlich ihre Reservierung.</p>

  <p class="my-2">
    Sollte sich noch etwas bezüglich der Personenanzahl ändern,
    bitten wir sie uns dies bis spätestens 14 Tage vor Veranstaltung schriftlich mitzuteilen.
  </p>

</x-app-layout>
