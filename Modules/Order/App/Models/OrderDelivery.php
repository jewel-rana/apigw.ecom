<?php

namespace Modules\Order\App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    protected $fillable = [
        'order_id',
        'customer_id',
        'delivery_to',
        'deliver_type',
        'status'
    ];

    protected $casts = [
        'order_id' => 'integer',
        'customer_id' => 'integer',
        'delivery_to' => 'string',
        'delivery_type' => 'string',
        'status' => 'boolean'
    ];
}
