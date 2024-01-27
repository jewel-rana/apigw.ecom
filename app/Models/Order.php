<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'promotion_id',
        'promotion_objective_id',
        'promotion_period',
        'gender',
        'min_age',
        'max_age',
        'amount',
        'location',
        'divisions',
        'status'
    ];

    protected $casts = [
        'divisions' => 'array'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $attributes = [
        'divisions' => []
    ];
}
