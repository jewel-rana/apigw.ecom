<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromotionObjective extends Model
{
    use HasFactory;

    public function params(): HasMany
    {
        return $this->hasMany(PromotionObjectiveParam::class);
    }

    public function format(): array
    {
        return $this->only(['id', 'name']) +
            [
                'params' => $this->params->map(function(PromotionObjectiveParam $param) {
                    return $param->format();
                })
            ];
    }
}
