<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\PromotionObjective;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionObjectiveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promotion::all()
            ->each(function (Promotion $promotion) {
                for ($i = 0; $i <= 5; $i++) {
                    $promotion->objectives()->save(new PromotionObjective(['name' => 'Object ' . mt_rand(1, 99)]));
                }
            });
    }
}
