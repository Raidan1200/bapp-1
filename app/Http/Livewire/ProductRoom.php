<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductRoom extends Component
{
    public $room;

    public function render()
    {
        return view('livewire.product-room', [
            'assignedProducts' => $this->room->products,
            'otherProducts' => $this->otherProducts()
        ]);
    }

    protected function otherProducts()
    {
        return $this->room->venue->products()->whereNotIn('id', $this->room->products->pluck('id'))->get();
    }

    public function add(Product $product)
    {
        $this->room->products()->attach($product->id);
        $this->room->refresh();
    }

    public function remove(Product $product)
    {
        $this->room->products()->detach($product->id);
        $this->room->refresh();
    }
}
