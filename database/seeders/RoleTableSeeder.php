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
            'customer'
        ];

        foreach ($roles as $role) {
             Role::create(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
