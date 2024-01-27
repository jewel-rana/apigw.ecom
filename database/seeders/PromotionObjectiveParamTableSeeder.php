<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\PromotionObjective;
use App\Models\PromotionObjectiveParam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionObjectiveParamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PromotionObjective::all()
            ->each(function (PromotionObjective $promotionObjective) {
                for ($i = 0; $i <= 2; $i++) {
                    $promotionObjective->params()->save(new PromotionObjectiveParam(['key' => 'foo ' . $i, 'label' => 'Foo ' . $i]));
                }
            });
    }
}
