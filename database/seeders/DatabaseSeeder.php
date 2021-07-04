<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleAndPermissionSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RoleAndPermissionSeeder::class);

        $admin = \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@bapp.de',
        ]);

        $admin->assignRole('admin');

        if (\Illuminate\Support\Facades\App::environment('local')) {
            $this->call(DevSeeder::class);
        }
    }
}
