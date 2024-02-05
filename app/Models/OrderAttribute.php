<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAttribute extends Model
{
    protected $fillable = [
        'key',
        'value'
    ];

    protected $hidden = [
        'order_id',
        'created_at',
        'updated_at'
    ];
}
