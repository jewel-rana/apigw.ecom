<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = config('scopes.permissions');

        $role = Role::where('name', 'admin')->first();

        foreach ($permissions as $permission => $description) {
            $permission = Permission::create(['name' => $permission]);
            $permission->assignRole($role);
        }
    }
}
