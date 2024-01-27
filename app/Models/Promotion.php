<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    use HasFactory;

    public function objectives(): HasMany
    {
        return $this->hasMany(PromotionObjective::class);
    }

    public function format(): array
    {
        return $this->only(['id', 'name']) +
            [
                'objectives' => $this->objectives->map(function(PromotionObjective $objective) {
                    return $objective->format();
                })
            ];
    }
}
