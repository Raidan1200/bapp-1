<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreatePackageRequest;

class PackageController extends Controller
{
    public function create(Request $request)
    {
        $this->authorize('create packages');

        return view('packages.create', [
            'venue_id' => $request->query('venue')
        ]);
    }

    public function store(CreatePackageRequest $request)
    {
        $validated = $request->validated();

        $validated['is_flat'] = $request->has('is_flat');

        if ($request->file('image')) {
            $path = $request->file('image')->store('images');
            $validated['image'] = $path;
        }

        $venue = Venue::findOrFail($validated['venue_id']);
        $package = $venue->packages()->create($validated);

        return redirect()
            ->route('venues.show', $venue);
    }

    public function edit(Package $package)
    {
        $this->authorize('modify packages');

        return view('packages.create', compact('package'));
    }

    public function update(CreatePackageRequest $request, Package $package)
    {
        $this->authorize('create packages');

        $validated = $request->validated();

        $validated['is_flat'] = $request->has('is_flat');

        // LATER: Package image upload
        // if ($request->file('image')) {
        //     Storage::delete($package->image);

        //     $path = $request->file('image')->store('images');
        //     $validated['image'] = $path;
        // }

        $package->update($validated);

        return redirect()
            ->route('venues.show', $package->venue)
            ->with('status', 'Produkt wurde aktualisiert.');
    }

    public function destroy(Package $package)
    {
        $this->authorize('delete packages');

        Storage::delete($package->image);

        $package->rooms()->sync([]); // LATER: Do I need this? DB cascade?
        $package->delete();

        return redirect()
            ->route('venues.show', $package->venue)
            ->with('status', 'Produkt wurde gelöscht.');
    }
}
