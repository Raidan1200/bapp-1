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
            'create users',    'modify users',    'delete users',
            'create tokens',                      'delete tokens',
            'create venues',   'modify venues',   'delete venues',
            'create products', 'modify products', 'delete products',
                               'modify orders',   'delete orders',
        ])->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        Permission::insert($permissions->toArray());

        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'user'])
            ->givePermissionTo([
                'modify orders'
            ]);
    }
}
