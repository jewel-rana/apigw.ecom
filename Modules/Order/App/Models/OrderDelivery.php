<?php

namespace Modules\Order\App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    protected $fillable = [
        'shipping_id',
        'order_id',
        'customer_id',
        'city',
        'address',
        'note',
        'remarks',
        'status'
    ];

    protected $casts = [
        'order_id' => 'integer',
        'customer_id' => 'integer',
        'shipping_id' => 'string',
        'city' => 'string',
        'address' => 'string',
        'note' => 'string',
        'remarks' => 'string',
        'status' => 'string'
    ];

    public function format(): array
    {
        return $this->only(['id', 'city', 'address', 'note', 'remarks', 'note']);
    }
}
