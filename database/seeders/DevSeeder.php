<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Database\Seeders\RoleAndPermissionSeeder;

class DevSeeder extends Seeder
{
    public function run()
    {
        // Users
        $admin = \App\Models\User::first();

        $user = \App\Models\User::factory()->create([
            'name' => 'User',
            'email' => 'user@bapp.de',
        ]);

        $user->assignRole('user');

        // Venues
        $venues = \App\Models\Venue::factory(3)
            ->has(Product::factory()->count(3))
            ->create();

        $venues[0]->users()->attach([$admin->id, $user->id]);
        $venues[1]->users()->attach($user->id);
    }
}
