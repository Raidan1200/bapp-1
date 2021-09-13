<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Items extends Component
{
    use AuthorizesRequests;

    public $items;

    public $data;

    public $orderId;

    public bool $editing = false;

    public $foundProducts = [];
    public $row;

    public $rules = [
        'items.*.id' => 'nullable',
        'items.*.product_name' => 'required|string|max:255',
        'items.*.quantity' => 'required|numeric',
        'items.*.unit_price' => 'required|numeric',
        'items.*.vat' => 'required|numeric',
        'items.*.state' => 'required|in:stored,new,delete',
    ];

    public function mount()
    {
        foreach ($this->items as &$item) {
            $item['state'] = 'stored';
        }

        $this->data = $this->items;
    }

    public function startEditing()
    {
        $this->editing = true;
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->items = $this->data;
        $this->editing = false;
    }

    public function save()
    {
        $this->authorize('modify orders');

        $this->validate();

        // TODO This is REAAAALLY inefficient
        //      Does Laravel have something like "bulkUpdate" or "updateMany"?

        foreach ($this->items as $item) {
            if ($item['state'] === 'delete' || $item['state'] === 'stored') {
                $model = Item::find($item['id']);

                if ($item['state'] === 'delete') {
                    $model->delete();
                }

                if ($item['state'] === 'stored') {
                    $model->update($item);
                    $newItems[] = $item;
                }
            }

            if ($item['state'] === 'new') {
                $item['order_id'] = $this->orderId;

                $newItem = Item::create($item);
                $item['id'] = $newItem->id;
                $item['state'] = 'stored';

                $newItems[] = $item;
            }
        }

        $this->editing = false;
        $this->items = $newItems;
        $this->data = $newItems;
        $this->emitUp('updateItems');
    }

    public function addRow()
    {
        $this->resetValidation();

        $this->items[] = [
            'product_name' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'vat' => 20,
            'state' => 'new'
        ];
    }

    public function removeRow($key)
    {
        $this->resetValidation();

        switch ($this->items[$key]['state']) {
            case 'stored':
                $this->items[$key]['state'] = 'delete';
                break;

            case 'delete':
                $this->items[$key]['state'] = 'stored';
                break;

            case 'new':
                array_splice($this->items, $key, 1);
                break;
        }
    }

    public function updating($row, $search)
    {
        $this->row = str_replace(['items.', '.product_name'], ['', ''], $row);

        if (mb_strlen($search) >= 3) {
            $this->foundProducts = $this->findProducts($search);
        } else {
            $this->foundProducts = [];
        }
    }

    public function findProducts($search) {
        return Product::where('name', 'like', "%{$search}%")
            ->orderBy('name')
            ->get();
    }

    public function fillFields($key, $product)
    {
        $this->items[$key] = [
            'product_name' => $product['name'],
            'quantity' => 1,
            'unit_price' => $product['unit_price'],
            'vat' => $product['vat'],
            'state' => 'new'
        ];

        $this->foundProducts = [];
    }

    public function render()
    {
        return view('livewire.items');
    }
}
