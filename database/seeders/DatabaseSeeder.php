<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Database\Seeders\RoleAndPermissionSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@bapp.de',
        ]);

        $admin->assignRole('admin');

        if (App::environment('local')) {
            $this->call(UserSeeder::class);
            $this->call(VenueSeeder::class);
            $this->call(OrderSeeder::class);
        }
    }
}
