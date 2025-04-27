<?php

namespace Modules\Region\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Region\Entities\Currency;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = (array)json_decode(file_get_contents(__DIR__ . '/../../currencies.json'));
        foreach($currencies as $currency) {
            Currency::create(['name' => $currency->name, 'code' => $currency->cc, 'symbol' => $currency->symbol]);
        }
    }
}
