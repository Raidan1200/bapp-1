{{-- Headline --}}
<div class="w-100 flex justify-between mt-2">

  {{-- Headline Left --}}
  <div>
    <button
      @click="state.showCustomer = !state.showCustomer"
      class="flex-1 text-left hover:text-primary-dark rounded px-2 -mx-2 font-semibold"
    >
      <div x-text="getCustomer()"></div>
    </button>
    <div>
      <a href="" x-show="order.deposit_invoice_id" x-text="order.deposit_invoice_id"></a>
      <a href="" x-show="order.interim_invoice_id" x-text="order.interim_invoice_id"></a>
      <a href="" x-show="order.final_invoice_id" x-text="order.final_invoice_id"></a>
      <a href="" x-show="!(order.deposit_invoice_id || order.interim_invoice_id || order.final_invoice_id)">Noch keine Rechnung</a>
    </div>
  </div>

  {{-- Headline Right --}}
  <div>
    <div class="font-semibold text-right">{{ $order->starts_at->timezone('Europe/Berlin')->formatLocalized('%a %d.%m %H:%M') }}</div>
    @isset ($order->latestAction)
      <div title="Von: '{{ $order->latestAction->from }}' Zu: '{{ $order->latestAction->to }}'">
        <span>{{ $order->latestAction->created_at->diffForHumans() }}</span>:
        <span class="font-semibold">{{ $order->latestAction->user_name }}</span>: {{ $order->latestAction->what }}
      </div>
    @endisset
  </div>
</div>
