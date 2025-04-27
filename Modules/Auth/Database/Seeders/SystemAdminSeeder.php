<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Auth\Constants\AuthConstant;
use Modules\Auth\Entities\User;

class SystemAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'System Admin',
            'email' => 'system@admin.com',
            'password' => Hash::make(Str::random(18)),
            'email_verified_at' => now(),
            'status' => AuthConstant::USER_ACTIVE,
            'is_editable' => AuthConstant::USER_NOT_EDITABLE
        ]);
        $user->assignRole('admin');
    }
}
