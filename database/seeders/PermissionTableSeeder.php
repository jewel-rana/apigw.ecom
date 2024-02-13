<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-show',
            'user-delete',
            'user-action',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'customer-list',
            'customer-create',
            'customer-update',
            'customer-delete',
            'order-list',
            'order-create',
            'order-update',
            'order-delete'
        ];

        $role = Role::where('name', 'admin')->first();

        foreach ($permissions as $permission) {
            $permission = Permission::create(['name' => $permission]);
            $permission->assignRole($role);
        }
    }
}
