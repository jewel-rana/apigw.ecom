<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Gateway\Constants\GatewayConstant;
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
                'status' => GatewayConstant::ACTIVE,
                'class_name' => 'App\Gateways\COD'
            ]
        );
    }
}
