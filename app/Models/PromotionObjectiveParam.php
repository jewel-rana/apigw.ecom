<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionObjectiveParam extends Model
{
    protected $attributes = [
        'items' => []
    ];

    public function format(): array
    {
        return $this->only(['id', 'type', 'key', 'label', 'placeholder']);
    }
}
