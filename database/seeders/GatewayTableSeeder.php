<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Gateway\Entities\Gateway;

class GatewayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gateway::updateOrCreate(
            [
                'name' => 'Cash On Delivery'
            ],
            [
                'name' => 'Cash On Delivery',
                'code' => 'COD',
                'class_name' => 'App\Gateways\COD'
            ]
        );
    }
}
