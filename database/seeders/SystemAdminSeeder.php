<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Constants\AuthConstant;

class SystemAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $user = User::create([
            'name' => 'System',
            'mobile' => '+8801900000001',
            'email' => 'system@admin.com',
            'password' => bcrypt(Str::random(18)),
            'email_verified_at' => now(),
            'status' => AuthConstant::USER_ACTIVE,
            'is_system' => AuthConstant::USER_IS_SYSTEM,
            'type' => AuthConstant::TYPE_ADMIN
        ]);
        $user->assignRole('admin');
    }
}
