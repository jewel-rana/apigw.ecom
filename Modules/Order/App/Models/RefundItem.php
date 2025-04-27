<?php

namespace Modules\Order\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Bundle\Entities\Bundle;
use Modules\Operator\Entities\Operator;

class RefundItem extends Model
{
    protected $fillable = [
        'order_id',
        'order_refund_id',
        'order_item_id',
        'operator_id',
        'bundle_id',
        'amount',
        'qty'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }
}
