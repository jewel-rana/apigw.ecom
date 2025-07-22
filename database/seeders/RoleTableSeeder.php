<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin',
            'manager',
            'moderator'
        ];

        foreach ($roles as $role_name) {
            Role::updateOrCreate(
                [
                    'name' => $role_name,
                ],
                [
                    'name' => $role_name,
                    'guard_name' => 'web'
                ]
            );
        }
    }
}
