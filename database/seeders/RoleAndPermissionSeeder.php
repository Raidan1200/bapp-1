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
            'create users',    'modify users',                      'delete users',
            'create tokens',                                        'delete tokens',
            'create venues',   'modify venues',   'admin venues',   'delete venues',
            'create rooms',    'modify rooms',    'admin rooms',    'delete rooms',
            'create packages', 'modify packages', 'admin packages', 'delete packages',
            'create products', 'modify products',                   'delete products',
                               'modify orders',   'admin orders',   'delete orders',
        ])->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        Permission::insert($permissions->toArray());

        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'manager'])
            ->givePermissionTo([
                'create users', 'modify users',
                'modify venues', 'modify rooms', 'modify packages',
                'modify orders', 'delete orders'
            ]);

        Role::create(['name' => 'employee'])
            ->givePermissionTo([
                'modify orders'
            ]);
    }
}
