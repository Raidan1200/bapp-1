      {{-- Footer --}}
      <div class="flex">

        {{-- Rechnungen --}}
        <div class="relative">
          <button
            x-on:click="state.invoiceDropdown = ! state.invoiceDropdown"
            @click.away="state.invoiceDropdown = false"
            @close.stop="state.invoiceDropdown = false"
            type="button"
            class="flex items-center mr-4 hover:bg-gray-100 transition duration-150 ease-in-out"
          >
            <div>Rechnungen</div>
            <div class="ml-1">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </div>
          </button>
          <template x-if="state.invoiceDropdown">
            <div class="absolute border rounded">
              <div
              @click="downloadInvoice('deposit')"
                x-show="order.deposit_invoice_at"
                x-text="'Anzahlungsrechnung' + (order.deposit_paid_at ? ' &#10003;' : '')"
                class="m-2 cursor-pointer"
              ></div>
              <div
                x-show="order.interim_invoice_at"
                @click="downloadInvoice('interim')"
                x-text="'Abschlussrechnung' + (order.interim_paid_at ? ' &#10003;' : '')"
                class="m-2 cursor-pointer"
              ></div>
              <div
                x-show="order.final_invoice_at"
                x-text="'Gesamtrechnung' + (order.final_paid_at ? ' &#10003;' : '')"
                @click="downloadInvoice('final')"
                class="m-2 cursor-pointer"
              ></div>
              <div
                x-show="order.state === 'cancelled'"
                x-text="'Stornorechnung'"
                class="m-2 cursor-pointer"
                @click="downloadInvoice('cancelled')"
              ></div>
            </div>
          </template>
        </div>

        {{-- Emails --}}
        <div class="relative">
          <button
            @click="state.emailsDropdown = ! state.emailsDropdown"
            @click.away="state.emailsDropdown = false"
            @close.stop="state.emailsDropdown = false"
            type="button"
            class="flex items-center mr-4 hover:bg-gray-100 transition duration-150 ease-in-out"
          >
            <div>Emails</div>
            <div class="ml-1">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </div>
          </button>
          <template x-if="state.emailsDropdown">
            <div
              x-show="state.emailsDropdown"
              class="absolute border rounded"
            >
              <div
                x-show="order.deposit_invoice_at"
                x-text="'Anzahlungsrechnung' + (order.deposit_paid_at ? ' &#10003;' : '')"
                @click="sendEmail('deposit')"
                class="m-2 cursor-pointer"
              ></div>
              <div
                x-show="order.interim_invoice_at"
                x-text="'Abschlussrechnung' + (order.interim_paid_at ? ' &#10003;' : '')"
                @click="sendEmail('interim')"
                class="m-2 cursor-pointer"
              ></div>
              <div
                x-show="order.final_invoice_at"
                x-text="'Gesamtrechnung' + (order.final_paid_at ? ' &#10003;' : '')"
                @click="sendEmail('final')"
                class="m-2 cursor-pointer"
              ></div>
              <div
                x-show="order.state === 'cancelled'"
                x-text="'Stornorechnung'"
                @click="sendEmail('cancelled')"
                class="m-2 cursor-pointer"
              ></div>
            </div>
          </template>
        </div>
      </div>
