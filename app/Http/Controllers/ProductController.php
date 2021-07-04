<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Venue $venue)
    {
        abort_unless(auth()->user()->can('create products'), 304);

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
        abort_unless(auth()->user()->can('create products'), 304);

        $validated = $request->validate([
            'name'        => 'required|max:255',
            'excerpt'     => 'sometimes',
            'description' => 'sometimes',
            'image'       => 'sometimes|mimes:jpg,jpeg,png,webp',
            'capacity'    => 'required',
            'price'       => 'required',
            'opens_at'    => ['required', 'min:0', 'max:24', fn($_, $value, $fail) => $value >= $request->closes_at ? $fail('Opening time cannot be equal to or after closing time.') : null],
            'closes_at'   => 'required|min:0|max:24',
            'deposit'     => 'required',
        ]);

        if ($request->file('image')) {
            $path = $request->file('image')->store('images');
            $validated['image'] = $path;
        }

        $product = $venue->products()->create($validated);

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
        abort_unless(auth()->user()->can('create products'), 304);

        $validated = $request->validate([
            'name'        => 'required|max:255',
            'excerpt'     => 'sometimes',
            'description' => 'sometimes',
            'image'       => 'sometimes|mimes:jpg,jpeg,png,webp',
            'capacity'    => 'required',
            'price'       => 'required',
            'opens_at'    => ['required', 'min:0', 'max:24', fn($_, $value, $fail) => $value >= $request->closes_at ? $fail('Opening time cannot be equal to or after closing time.') : null],
            'closes_at'   => 'required|min:0|max:24',
            'deposit'     => 'required',
        ]);

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
        abort_unless(auth()->user()->can('delete products'), 403);

        Storage::delete($product->image);
        $product->delete();

        return redirect(route('venues.show', $product->venue));
    }
}
