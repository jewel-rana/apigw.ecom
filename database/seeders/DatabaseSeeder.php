<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(SystemAdminSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(CustomerTableSeeder::class);
    }
}
