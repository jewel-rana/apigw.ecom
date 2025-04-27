<?php

namespace Modules\Payment\App\Models;

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Gateway\Entities\Gateway;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Models\Refund;
use Modules\Payment\App\Constants\PaymentConstant;

class Payment extends Model
{
    protected $guarded = ['id'];
    protected $hidden = [
        'gateway_id',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i a',
        'updated_at' => 'datetime:d/m/Y h:i a',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class, 'order_id', 'order_id');
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function createLog(): HasOne
    {
        return $this->hasOne(PaymentLog::class)
            ->where('type', PaymentConstant::CREATE)
            ->latest();
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('order_id')) {
            $query->where('order_id', $request->input('order_id'));
        }

        if($request->filled('date_from')) {
            $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('date_from'));
            $query->where('created_at', '>=', $dateFrom->startOfDay());
        }

        if($request->filled('date_to')) {
            $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('date_to'));
            $query->where('created_at', '<=', $dateTo->endOfDay());
        }

        if($request->filled('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

        if($request->filled('gateway_id')) {
            $query->where('gateway_id', $request->input('gateway_id'));
        }

        if($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        if($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('order_id', (int) $request->input('keyword'))
                    ->orWhere('customer_id', (int) $request->input('keyword'));
            });
        }

        return $query;
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }

    public function canTakeAction(): bool
    {
        return $this->getRawOriginal('created_at') <= now()->subMinutes(15) &&
            CommonHelper::hasPermission(['payment-action']) &&
            $this->getRawOriginal('status') != PaymentConstant::STATUS_SUCCESS;
    }

    public function format($single = false): array
    {
        return $this->only(['id', 'order_id', 'amount', 'status', 'created_at']);
    }

    public function paymentProcessingPeriodExpired(): bool
    {
        return now()->subMinutes(PaymentConstant::PAYMENT_VERIFICATION_PERIOD)
                ->gte(Carbon::createFromFormat('d/m/Y h:i a', $this->created_at)) === true;
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($payment) {
            $payment->logs()->delete();
        });
    }
}

