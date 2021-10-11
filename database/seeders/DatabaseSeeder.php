<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RoleAndPermissionSeeder::class);

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@bapp.de',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => null,
            'image' => null,
        ]);

        $admin->assignRole('admin');

        $this->call(ZauberSeeder::class);

        // TODO: remove comment!!!
        // if (App::environment('local')) {
            $this->call(UserSeeder::class);
            $this->call(VenueSeeder::class);
            $this->call(OrderSeeder::class);
        // }
    }
}
