<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('modify users');

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

    public function store(CreateUserRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($request->password);

        $user = User::create($validated);
        $user->assignRole($validated['role']);

        event(new Registered($user));

        return redirect()->route('users.edit', $user);
    }

    public function edit(User $user)
    {
        abort_unless(auth()->user()->can('modify users') || auth()->user()->id === $user->id, 403);

        return view('users.create', [
            'user' => $user->load(['roles', 'venues']),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        if ($validated['password'] === null) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        if ($user->isLastAdmin() && $validated['role'] != 1) {
            return redirect(route('users.edit', $user))
                ->withInput()
                ->with('error', 'Dies ist der letzte Admin. Er muss die Galaxie retten und muss folglich Admin bleiben.');
        }

        if (auth()->user()->can('modify users') && ! $user->hasRole($validated['role'])) {
            $user->syncRoles($validated['role']);
        }

        return redirect()->route('users.edit', $user);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete users');

        if ($user->isLastAdmin()) {
            return redirect(route('users.edit', $user))
                ->with('error', 'Der letzte Administrator kann nicht gelöscht werden.');
        }

        $user->venues()->sync([]); // TODO: Do I need this? DB cascade?
        $user->delete();

        return redirect(route('users.index'));
    }
}
