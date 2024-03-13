<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'customer_id',
        'order_id',
        'amount',
        'payment_method',
        'gateway_payment_id',
        'gateway_trx_id',
        'gateway_response',
        'status'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (Payment $payment) {
            $order = Order::find($payment->order_id);
            $payment['customer_id'] = $order->customer_id;
            $payment['amount'] = $order->amount;
        });
    }
}
