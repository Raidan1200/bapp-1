<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Customer extends Component
{
    use AuthorizesRequests;

    public $customer;

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
        $this->authorize('modify customers');

        $this->validate();
        $this->customer->save();

        $this->emitUp('updateCustomer');
        $this->editing = false;
    }

    public function render()
    {
        return view('livewire.customer');
    }
}
