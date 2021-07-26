<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function index()
    {
        // TODO ROLLO: Add permission to view users?
        return view('users.index', [
            'users' => User::orderBy('name')->with('venues')->get()
        ]);
    }

    public function create()
    {
        $this->authorize('create users');

        return view('users.create', [
            'roles' => Role::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create users');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|exists:roles,id',
            'image' => 'sometimes|mimes:jpg,jpeg,png,webp',
        ]);

        if ($request->file('image')) {
            $path = $request->file('image')->store('images');
            $validated['image'] = $path;
        }

        $validated['password'] = Hash::make($request->password);

        $user = User::create($validated);
        $user->assignRole($validated['role']);

        event(new Registered($user));

        return redirect(route('users.show', $user));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('modify users');

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),

            // TODO IMPORTANT: Filter venues already attached to the user
            'venues' => Venue::orderBy('name')->get(),
        ]);
    }

    public function delete(Request $request, User $user)
    {

    }
}
