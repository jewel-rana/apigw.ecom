<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class PromotionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promotions = ['Facebook', 'Youtube', 'Google Adsense'];
        foreach ($promotions as $promotion) {
            Promotion::create(['name' => $promotion]);
        }
    }
}
