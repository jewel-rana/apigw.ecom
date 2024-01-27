<?php

namespace Database\Seeders;

use App\Models\PromotionObjective;
use App\Models\PromotionObjectiveParam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class PromotionObjectiveParamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();
        PromotionObjective::all()
            ->each(function (PromotionObjective $promotionObjective) {
                for ($i = 0; $i <= 2; $i++) {
                    PromotionObjectiveParam::create([
                        'promotion_objective_id' => $promotionObjective->id,
                        'key' => "foo {$i}",
                        'label' => "Foo {$i}",
                        'placeholder' => "Foo $i"
                    ]);
                }
            });
    }
}
