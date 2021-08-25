<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customer;

class CustomerSearch extends Component
{
    public $customerName = '';

    public $customers = [];

    public function findCustomers($name) {
        return Customer::where('first_name', 'like', "%{$name}%")
            ->orWhere('last_name', 'like', "%{$name}%")
            ->orWhere('company', 'like', "%{$name}%")
            ->distinct()
            ->get();
    }

    public function render()
    {
        if (mb_strlen($this->customerName) >= 3) {
            $this->customers = $this->findCustomers($this->customerName);
        } else {
            $this->customers = [];
        }

        return view('livewire.customer-search');
    }
}
