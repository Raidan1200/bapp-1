<?php

namespace App\Http\Livewire;

use App\Models\Venue;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Http\Request;

class Products extends Component
{
    public $venue;
    public $products;

    public $adding = false;
    public $newProduct = null;

    public $editingIndex = null;
    public $editingProduct = null;

    public $validationAttributes = [
        'products.*.name' => 'name',
    ];

    public function mount(Request $request)
    {
        $this->venue = $request->query('venue');
    }

    public function add()
    {
        $this->newProduct = [
            'name' => '',
            'unit_price' => '',
            'vat' => '',
        ];

        $this->adding = true;
    }

    public function store()
    {
        $this->validate([
            'newProduct.name' => 'required',
            'newProduct.unit_price' => 'required|numeric',
            'newProduct.vat' => 'required|numeric'
        ]);

        $product = Product::create([
            'name' => $this->newProduct['name'],
            'unit_price' => $this->newProduct['unit_price'],
            'vat' => $this->newProduct['vat'],
            'venue_id' => $this->venue
        ]);

        array_unshift($this->products, $product);

        $this->add();
    }

    public function cancelAdd()
    {
        $this->adding = false;
        $this->newProduct = null;
    }

    public function edit($productIndex)
    {
        $this->editingIndex = $productIndex;
        $this->editingProduct = $this->products[$productIndex];
    }

    public function save($productIndex)
    {
        $this->validate([
            'products.*.name' => 'required',
            'products.*.unit_price' => 'required|numeric',
            'products.*.vat' => 'required|numeric'
        ]);

        $product = $this->products[$productIndex];

        $productModel = Product::find($product['id']);

        $productModel->update([
            'name' => $product['name'],
            'unit_price' => $product['unit_price'],
            'vat' => $product['vat'],
        ]);

        $this->editingIndex = null;
        $this->editingProduct = null;
    }

    public function cancelEdit()
    {
        $this->products[$this->editingIndex] = $this->editingProduct;
        $this->editingIndex = null;
        $this->editingProduct = null;
    }

    public function delete($productIndex)
    {
        $product = Product::find($this->products[$productIndex]['id']);
        $product->delete();
        array_splice($this->products, $productIndex, 1);
    }

    public function render()
    {
        $this->products = Venue::findOrFail($this->venue)->products->toArray();

        return view('livewire.products');
    }
}
