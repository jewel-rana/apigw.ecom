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
            'administrator-list',
            'administrator-create',
            'administrator-edit',
            'administrator-show',
            'administrator-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-show',
            'permission-delete',
            'provider-list',
            'provider-create',
            'provider-update',
            'provider-delete',
            'vendor-list',
            'vendor-create',
            'vendor-update',
            'vendor-delete',
            'bundle-list',
            'bundle-create',
            'bundle-update',
            'bundle-delete'
        ];

        $role = Role::where('name', 'admin')->first();

        foreach ($permissions as $permission) {
            $permission = Permission::create(['name' => $permission]);
            $permission->assignRole($role);
        }
    }
}
