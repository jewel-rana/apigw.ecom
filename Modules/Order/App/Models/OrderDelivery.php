<?php

namespace Modules\Order\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Shipping\Entities\Shipping;

class OrderDelivery extends Model
{
    protected $fillable = [
        'shipping_id',
        'order_id',
        'customer_id',
        'city',
        'address',
        'postal_code',
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

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(Shipping::class);
    }

    public function format(): array
    {
        return $this->only(['id', 'city', 'address', 'postal_code', 'note', 'remarks', 'note']);
    }
}
