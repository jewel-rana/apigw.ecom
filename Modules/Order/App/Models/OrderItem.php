<?php

namespace Modules\Order\App\Models;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Bundle\Entities\Bundle;
use Modules\Operator\Entities\Operator;
use Modules\Operator\Enums\OperatorEnums;
use Modules\Payment\App\Constants\PaymentConstant;
use Modules\Payment\App\Models\Payment;
use Modules\Voucher\Entities\Voucher;

class OrderItem extends Model
{
    use ActivityTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'data' => 'array',
        'is_remote_voucher' => 'boolean',
        'created_at' => 'datetime:d/m/Y h:i a',
        'updated_at' => 'datetime:d/m/Y h:i a',
    ];

    protected static $logAttributes = ['name', 'guard_name'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Role {$eventName}";
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id')->latest();
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class)->latest();
    }

    public function isVoucher(): bool
    {
        return $this->operator->deliverable_type === OperatorEnums::TYPE_VOUCHER;
    }

    public function scopeFilter($query, $request, $customerId = null)
    {
        if(!$request->filled('keyword') && !$request->filled('order_id')) {
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

        if ($request->filled('operator_id')) {
            $query->where('operator_id', (int)$request->input('operator_id'));
        }

        if ($request->filled('bundle_id')) {
            $query->where('bundle_id', (int)$request->input('bundle_id'));
        }

        if ($request->filled('customer_id')) {
            $query->whereHas('order', function ($query) use ($request) {
                $query->where('customer_id', $request->input('customer_id'));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('payment', function ($query) use ($request) {
                $query->where('status', (string) $request->input('payment_status'));
            });
        }

        if ($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                if (is_numeric($request->input('keyword'))) {
                    $query->where('id', (int) $request->input('keyword'));
                    $query->orWhere('order_id', (int)$request->input('keyword'));
                } else {
                    $query->orWhereHas('customer', function ($query) use ($request) {
                        if (is_numeric($request->input('keyword'))) {
                            $query->where('id', $request->input('keyword'));
                        } else {
                            $query->where(function ($query) use ($request) {
                                $query->where('name', 'like', '%' . $request->input('keyword') . '%');
                                $query->orWhere('mobile', 'like', '%' . $request->input('keyword') . '%');
                                $query->orWhere('email', 'like', '%' . $request->input('keyword') . '%');
                            });
                        }
                    });
                }
            });
        }

        return $query;
    }

    public function getStatusAttribute($value): string
    {
        try {
            $status = $value;
            if (!in_array($this->order?->payment?->status, [
                PaymentConstant::STATUS_SUCCESS,
                PaymentConstant::STATUS_PROCESSING,
                PaymentConstant::STATUS_PENDING
            ])) {
                $status = PaymentConstant::STATUS_FAILED;
            }
        } catch (\Exception $exception) {
            LogHelper::debug($exception->getMessage(), [
                'keyword' => 'ORDER_ITEM_FAILED_STATUS',
            ]);
        }
        return $status;
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }

    public function getUpdatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }

    public function format(): array
    {
        return $this->only([
                'id',
                'qty',
                'unit_price',
                'discount',
                'coupon_discount',
                'total_price',
                'data',
                'status'
            ]) +
            [
                'operator' => $this->operator->only(['id', 'name', 'thumbnail', 'is_in_app_deliverable']),
                'product' => $this->bundle?->only(['id', 'name', 'thumbnail']) ?? $this->operator->only(['id', 'name', 'thumbnail']),
                'is_voucher' => $this->isVoucher(),
                'is_in_app_deliverable' => $this->operator->is_in_app_deliverable
            ];
    }

    public function itemInfo(): array
    {
        return [
            'Operator' => $this->operator->name,
            'Denomination' => $this->bundle->name,
            'Order created' => $this->created_at,
            'Payment status' => $this->order->payment?->status,
            'Purchase status' => $this->status
        ];
    }

    public static function boot(): void
    {
        parent::boot();
        static::created(function ($model) {
            $model->update([
                'trx_id' => $model->order_id . $model->id,
            ]);
        });
    }
}
