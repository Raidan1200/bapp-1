<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customer as CustomerModel;

class Customer extends Component
{
    public CustomerModel $customer;

    public bool $editing = false;

    protected $rules = [
        'customer.first_name' => 'required|string|max:255',
        'customer.last_name' => 'nullable|string|max:255',
        'customer.company' => 'nullable|string|max:255',
        'customer.email' => 'required',
        'customer.street' => 'required',
        'customer.street_no' => 'required',
        'customer.zip' => 'required',
        'customer.city' => 'required',
        'customer.phone' => 'required',
    ];

    public function startEditing()
    {
        $this->editing = true;
    }

    public function cancel()
    {
        $this->editing = false;
    }

    public function save()
    {
        $this->validate();
        $this->customer->save();

        $this->emit('updateBookings');
        $this->editing = false;
    }

    public function render()
    {
        return view('livewire.customer');
    }
}
