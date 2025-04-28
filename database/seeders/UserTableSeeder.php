<?php

namespace Database\Seeders;

use App\Constants\AuthConstant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();
        $user = User::create([
            'name' => 'Admin',
            'mobile' => '+8801911785317',
            'email' => 'jewel@newroztech.com',
            'password' => bcrypt('123456789'),
            'email_verified_at' => now(),
            'status' => 1,
            'type' => AuthConstant::TYPE_ADMIN
        ]);
        $user->assignRole('admin');
    }
}
