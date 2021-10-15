<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = collect([
            // All about config
            'create users',    'modify users',    'delete users',
            'create tokens',                      'delete tokens',
            'create venues',   'modify venues',   'delete venues',
            'create rooms',    'modify rooms',    'delete rooms',
            'create packages', 'modify packages', 'delete packages',
            'create products', 'modify products', 'delete products',

            // All about orders
            'modify orders',   'admin orders',   'delete orders',
            'modify bookings',
            'modify items',
            'modify customers',
            // We need more permission here!!!
        ])->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        Permission::insert($permissions->toArray());

        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'manager'])
            ->givePermissionTo([
                'create users', 'modify users',
                'modify venues',
                'modify rooms',
                'modify packages',
                'create products', 'modify products',
                'modify orders',
                'modify bookings',
                'modify items',
                'modify customers'
            ]);

        Role::create(['name' => 'employee'])
            ->givePermissionTo([
                'modify items',
            ]);
    }
}
