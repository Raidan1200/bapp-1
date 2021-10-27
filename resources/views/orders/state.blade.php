<x-form-field>
  @can('modify orders')
    <select
      x-model="order.state"
      class="py-0"
      name="order-status"
      id="order-status"
    >
      {{-- LATER: Find a more elegant solution ... please :) --}}
      <option
        value="fresh"
        :disabled="['deposit_paid', 'interim_paid', 'final_paid', 'cancelled', 'not_paid'].includes(order.state)"
      >
        {{ __('app.fresh') }}
      </option>
      <option
        value="deposit_paid"
        x-text="'{{ __('app.deposit_paid') }}' + (order.deposit_paid_at ? ' &#10003;' : '')"
        :disabled="['interim_paid', 'final_paid', 'cancelled', 'not_paid'].includes(order.state)"
        ></option>
      <option
        value="interim_paid"
        x-text="'{{ __('app.interim_paid') }}' + (order.interim_paid_at ? ' &#10003;' : '')"
        :disabled="['final_paid', 'cancelled', 'not_paid'].includes(order.state)"
      ></option>
      <option
        value="final_paid"
        x-text="'{{ __('app.final_paid') }}' + (order.final_paid_at ? ' &#10003;' : '')"
        :disabled="order.interim_is_final"
      ></option>
      <option value="cancelled">
        {{ __('app.cancelled') }}
      </option>
      <option value="not_paid">
        {{ __('app.not_paid') }}
      </option>
    </select>
  @else
    <div class="w-64">
      {{ __('app.'.$selectedState) }}
    </div>
  @endcan
</x-form-field>