<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $rules;

    public function __construct()
    {
        $rules = [
            'name'            => 'required|max:255',
            'slogan'          => 'sometimes',
            'description'     => 'sometimes',
            'image'           => 'sometimes|mimes:jpg,jpeg,png,webp',
            'starts_at'       => 'required|date',
            'ends_at'         => 'required|date',
            // TODO IMPORTANT: This is actually wrong
            'opens_at'        => ['required', 'min:0', 'max:24', fn($_, $value, $fail) => $value >= $request->closes_at ? $fail('Opening time cannot be equal to or after closing time.') : null],
            'closes_at'       => 'required|min:0|max:24',
            'min_occupancy'   => 'sometimes|integer',
            'unit_price'      => 'required|integer',
            'vat'             => 'required|numeric',
            'is_flat'         => 'sometimes|boolean',
            'unit_price_flat' => 'sometimes|integer',
            'vat_flat'        => 'sometimes|numeric',
            'deposit'         => 'required|numeric',
            'room_id'         => 'exists:rooms,id'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
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
    public function create(Venue $venue)
    {
        $this->authorize('create products');

        return view('products.create', compact('venue'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Venue $venue, Request $request)
    {
        $this->authorize('create products');

        $validated = $request->validate($this->rules);

        if ($request->file('image')) {
            $path = $request->file('image')->store('images');
            $validated['image'] = $path;
        }

        $product = $venue->products()->create($validated);

        Cache::forget('products');

        return redirect(route('products.show', $product));
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
    public function update(Product $product, Request $request)
    {
        $this->authorize('create products');

        $validated = $request->validate($this->rules);

        if ($request->file('image')) {
            $path = $request->file('image')->store('images');
            $validated['image'] = $path;
        }

        $product->update($validated);

        Cache::forget('products');

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
        $product->delete();

        Cache::forget('products');

        return redirect(route('venues.show', $product->venue));
    }
}
