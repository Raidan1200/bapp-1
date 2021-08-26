<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Venue;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Database\Seeders\RoleAndPermissionSeeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Users
        User::factory()->create([
            'name' => 'Manni Manager',
            'email' => 'manni@bapp.de',
        ])->assignRole('manager');

        User::factory()->create([
            'name' => 'Maria Manager',
            'email' => 'maria@bapp.de',
        ])->assignRole('manager');

        User::factory()->create([
            'name' => 'Emily Employee',
            'email' => 'emily@bapp.de',
        ])->assignRole('employee');

        User::factory()->create([
            'name' => 'Erwin Employee',
            'email' => 'erwin@bapp.de',
        ])->assignRole('employee');

        User::factory()->create([
            'name' => 'Ursula User',
            'email' => 'ursula@bapp.de',
        ]);
    }
}
