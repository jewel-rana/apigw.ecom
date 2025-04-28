<?php

namespace Modules\Auth\Database\Seeders;

use App\Constants\AppConstant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Entities\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'mobile' => '01844584584',
            'password' => Hash::make('123456789'),
            'email_verified_at' => now(),
            'status' => AppConstant::USER_ACTIVE
        ]);
        $user->assignRole('admin');
    }
}
