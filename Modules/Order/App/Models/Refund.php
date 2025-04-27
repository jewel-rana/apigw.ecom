<?php

namespace Modules\Order\App\Models;

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Gateway\Entities\Gateway;
use Modules\Payment\App\Constants\PaymentConstant;
use Modules\Payment\App\Models\Payment;

class Refund extends Model
{
    protected $fillable = [
        'order_id',
        'customer_id',
        'gateway_id',
        'gateway_refund_id',
        'payment_id',
        'amount',
        'status',
        'attempts',
        'remarks'
    ];

    protected $casts = [
        'attempts' => 'integer',
        'status' => 'string'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'order_id', 'order_id');
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RefundItem::class);
    }

    public function scopeFilter($query, $request)
    {
        if(!$request->filled('keyword') && !$request->get('order_id')) {
            if ($request->filled('date_from')) {
                $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('date_from'));
                $query->where('created_at', '>=', $dateFrom->startOfDay());
            }

            if ($request->filled('date_to')) {
                $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('date_to'));
                $query->where('created_at', '<=', $dateTo->endOfDay());
            }
        }

        if ($request->filled('order_id')) {
            $query->where('order_id', (int)$request->input('order_id'));
        }

        if ($request->filled('gateway_id')) {
            $query->where('gateway_id', (int)$request->input('gateway_id'));
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', (int)$request->input('customer_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', (string)$request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('payment', function($query) use ($request){
                $query->where('status', (string) $request->input('payment_status'));
            });
        }

        if ($request->filled('order_status')) {
            $query->whereHas('order', function($query) use ($request){
                $query->where('status', (string) $request->input('order_status'));
            });
        }

        if ($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('order_id', (int) $request->input('keyword'))
                    ->orWhere('id', (int)  $request->input('keyword'))
                    ->orWhere('customer_id', (int)  $request->input('keyword'));
            });
        }
        return $query;
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }

    public function getUpdatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }
}
