<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionObjectiveParam extends Model
{
    protected $fillable = [
        'promotion_objective_id',
        'type',
        'key',
        'label',
        'placeholder',
        'min_length',
        'max_length',
        'is_required',
        'items'
    ];

    protected $attributes = [
        'type' => 'text',
//        'items' => [],
        'min_length' => 2,
        'max_length' => 32,
        'is_required' => true
    ];

    protected $casts = [
        'items' => 'json'
    ];

    public function format(): array
    {
        return $this->only(['id', 'type', 'key', 'label', 'placeholder', 'min_length', 'max_length', 'is_required']);
    }
}
