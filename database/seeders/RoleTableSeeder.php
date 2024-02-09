<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
        	'admin',
            'manager',
            'moderator'
        ];

        foreach ($roles as $role_name) {
             Role::create(['name' => $role_name, 'guard_name' => 'web']);
        }
    }
}
