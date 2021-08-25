<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateProductRequest;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $this->authorize('create products');

        return view('products.create', [
            'venue_id' => $request->query('venue')
        ]);
    }

    public function store(CreateProductRequest $request)
    {
        $validated = $request->validated();

        $validated['is_flat'] = $request->has('is_flat');

        if ($request->file('image')) {
            $path = $request->file('image')->store('images');
            $validated['image'] = $path;
        }

        $venue = Venue::findOrFail($validated['venue_id']);
        $product = $venue->products()->create($validated);

        return redirect()
            ->route('venues.show', $venue);
    }

    // public function show(Product $product)
    // {
    //     return view('products.show', compact('product'));
    // }

    public function edit(Product $product)
    {
        $this->authorize('modify products');

        return view('products.create', compact('product'));
    }

    public function update(CreateProductRequest $request, Product $product)
    {
        $this->authorize('create products');

        $validated = $request->validated();

        $validated['is_flat'] = $request->has('is_flat');

        if ($request->file('image')) {
            Storage::delete($product->image); // TODO: Test this

            $path = $request->file('image')->store('images');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()
            ->route('venues.show', $product->venue)
            ->with('status', 'Produkt wurde aktualisiert.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete products');

        Storage::delete($product->image);

        $product->rooms()->sync([]); // TODO: Do I need this? DB cascade?
        $product->delete();

        return redirect()
            ->route('venues.show', $product->venue)
            ->with('status', 'Produkt wurde gelöscht.');
    }
}
