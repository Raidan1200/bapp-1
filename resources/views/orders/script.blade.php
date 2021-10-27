<script type="text/javascript">
  function orderEditor() {
    return {
      order: {
        customer: {},
      },
      state: {
        loading: true,
        editing: false,
        showCustomer: false,
        invoiceDropdown: false,
        emailsDropdown: false,
      },
      orderEditorSpread: {
      },
      async init() {
        this.state.loading = true
        url = "{{ route('orders.show', $order) }}"

        try {
          await this.fetchOrder()
        } catch (e) {
          console.error(e);
        }

        this.state.loading = false
      },
      formIsValid() {
        // Object.entries(rules).forEach(([field, rules]) => {

        //  });
        return true;
      },
      async fetchOrder() {
        const url = "{{ isset($order) ? route('orders.update', $order) : route('orders.store') }}"

        const response = await axios.get(url);
        const order = response.data.data

        order.bookings = order.bookings.map(booking => ({
          data: booking,
          state: 'stored',
        }))

        this.order = order
      },
      async save() {
        if (this.formIsValid()) {
          this.state.loading = true

          try {
              await axios.put(url, this.order);
              await this.fetchOrder()
          } catch (e) {
            console.error(e);
          }

          this.state.loading = false
        }
      },
      getCustomer() {
        if (! this.order) return ''

        return this.order.customer.company
          ? `${this.order.customer.company} (${this.order.customer.first_name} ${this.order.customer.last_name})`
          : `${this.order.customer.first_name} ${this.order.customer.last_name}`
      },
      downloadInvoice(type) {
        console.log(`downloadInvoice(${type})`);
      },
      sendEmail(type) {
        console.log(`sendEmail(${type})`);
      },
      deleteClicked(index) {
        const state = this.order.bookings[index].state;

        if (state === 'new') {
          this.order.bookings.splice(index, 1)
        }

        else if (state === 'stored') {
          this.order.bookings[index].state = 'delete'
        }

        else if (state === 'delete') {
          this.order.bookings[index].state = 'stored'
        }
      },
      colorForState(state) {
        const colors = {
          new: 'bg-green-200',
          delete: 'bg-red-200',
        };

        return colors[state] ?? ''
      },
      addRow() {
        this.order.bookings.push({
          data: {
            id: null,
            config: null,
            starts_at: null,
            ends_at: null,
            interval: null,
            package_name: '',
            unit_price: 100,
            quantity: 1,
            vat: 20,
            deposit: 100,
            is_flat: false,
            room_id: null,
            package_id: null,
            created_at: null,
          },
          state: 'new',
        })
      },
    }
  }
</script>