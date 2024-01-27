<?php

namespace Database\Seeders;

use App\Constants\AuthConstant;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();
        $user = Customer::create([
            'name' => 'Jewel Rana',
            'mobile' => '+8801911785317',
            'email' => 'jewelrana.dev@gmail.com',
            'password' => bcrypt('123456789'),
            'email_verified_at' => now(),
            'status' => AuthConstant::STATUS_ACTIVE
        ]);
    }
}
