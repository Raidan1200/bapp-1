<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            // TODO: This belongs in an API controller, doesn't it?
            // This is kind of misleading.
            // auth()->user() is the Token-authenticated Venue, NOT the user!
            return auth()->user()->products;
        }

        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create products');

        return view('products.create', [
            'venue_id' => $request->query('venue')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $validated = $request->validated();

        if ($request->file('image')) {
            $path = $request->file('image')->store('images');
            $validated['image'] = $path;
        }

        $venue = Venue::findOrFail($validated['venue_id']);
        $product = $venue->products()->create($validated);

        return redirect(route('venues.show', $venue));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $this->authorize('modify products');

        return view('products.create', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(CreateProductRequest $product, Request $request)
    {
        $this->authorize('create products');

        $validated = $request->validate($this->rules);

        if ($request->file('image')) {
            $path = $request->file('image')->store('images');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect(route('products.show', $product));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete products');

        Storage::delete($product->image);

        $product->rooms()->sync([]);
        $product->delete();

        return redirect()->route('venues.show', $product->venue)->with('status', 'Produkt wurde gelöscht.');
    }
}
