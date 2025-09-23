<?php

namespace Modules\Order\App\Models;

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Customer\App\Models\Customer;
use Modules\Order\App\Constant\OrderConstant;
use Modules\Payment\App\Models\Payment;
use Modules\Shipping\Entities\Shipping;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Order extends Model
{
    use ActivityTrait;

    protected $fillable = [
        'uuid',
        'customer_id',
        'country_id',
        'city_id',
        'code',
        'address',
        'total_qty',
        'total_amount',
        'shipping_cost',
        'discount',
        'coupon_discount',
        'total_payable',
        'status',
        'is_refund_initiated',
        'is_refunded',
        'remarks',
        'tracking_number'
    ];

    protected $hidden = [
        'customer_id',
        'shipping_id',
        'country_id',
        'city_id',
    ];

    protected $casts = [
        'is_refund_initiated' => 'boolean',
        'is_refunded' => 'boolean',
        'price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_payable' => 'decimal:2',
        'discount' => 'decimal:2'
    ];

    protected static $logAttributes = ['name', 'guard_name'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Order {$eventName}";
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(Shipping::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(OrderDelivery::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->latest();
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latest();
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function getStatusAttribute($value): string
    {
        return ucwords(str_replace('-', ' ', $value));
    }

    public function scopeFilter($query, $request, $customerId = null)
    {
        if ($request->filled('order_id')) {
            $query->where('id', $request->input('order_id'));
        }

        if (!$request->filled('keyword') && !$request->filled('order_id')) {
            if ($request->filled('date_from')) {
                $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('date_from'));
                $query->where('created_at', '>=', $dateFrom->startOfDay());
            }

            if ($request->filled('date_to')) {
                $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('date_to'));
                $query->where('created_at', '<=', $dateTo->endOfDay());
            }
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('id', (int)$request->input('keyword'));
                $query->orWhere('customer_id', (int)$request->input('keyword'));
            });
        }

        return $query;
    }

    public function getBadgeWithStatusAttribute($value): string
    {
        return '<span class="badge badge-' . CommonHelper::attachBadge($this->status) . '">' . $this->status . '</span>';
    }

    public function getInfoAttribute($value): ?string
    {
        $str = $this->status;
        if ($this->status != OrderConstant::COMPLETE) {
            if ($this->is_refunded) {
                $str = __('Refunded');
            } elseif ($this->is_refund_initiated) {
                $str = __('Refund initiated');
            }
        }
        return $str;
    }

    public function isNotOwner(): bool
    {
        return $this->customer_id != auth('customer')->id();
    }

    public function isOwner(): bool
    {
        return $this->customer_id == auth('customer')->id();
    }

    public function isWishListed(): bool
    {
        $isWishlisted = false;
        if (auth('customer')->check()) {
            $myWishlists = CommonHelper::getMyWishList(auth('customer')->id());
            if ($myWishlists && in_array($this->id, $myWishlists)) {
                $isWishlisted = true;
            }
        }

        return $isWishlisted;
    }

    public function format($single = true): array
    {
        $data = [
                'created_by' => $this->createdBy?->only(['id', 'name', 'email']),
                'updated_by' => $this->updatedBy?->only(['id', 'name', 'email']),
                'wishlisted' => $this->isWishListed(),
                'delivery' => $this->delivery?->format(),
                'shipping' => $this->delivery?->shipping?->format(),
            ] +
            $this->only(
                'id',
                'uuid',
                'tracking_number',
                'customer',
                'total_qty',
                'total_amount',
                'discount',
                'coupon_discount',
                'status',
                'remarks',
                'created_at',
                'updated_at'
            );

        if ($single) {
            $data['items'] = $this->items?->map(function ($item) {
                return $item->format(true);
            });
            $data['payment'] = $this->payment?->format();
        }

        return $data;
    }

    public function trackingFormat(): array
    {
        return $this->format(true) +
            [
                'lifecycle' => $this->histories->map(function ($history) {
                    return $history->format();
                })
            ];
    }

    public function formatCheck()
    {
        return $this->only(['id', 'uuid', 'status', 'total_payable']) +
            [
                'items' => $this->items->map(function ($item) {
                    return $item->only(['id', 'status']) + ['trxId' => $item->order_id . $item->id];
                }),
                'payment' => $this->payment?->only(['id', 'status']) + ['fibId' => $this->payment?->createLog?->gateway_payment_id]
            ];
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function (Order $order) {
            $order->uuid = self::uuid();
            $order->tracking_number = self::trackingNumber();
        });

        static::updating(function (Order $order) {
            if(!$order->tracking_number) {
                $order->tracking_number = self::trackingNumber();
            }
        });

        static::deleting(function ($order) {
            $order->items->each(function ($item) {
                $item->delete();
            });
            $order->payment?->delete();
        });
    }

    private static function uuid(): UuidInterface
    {
        while (1) {
            $uuid = Uuid::uuid4();
            if (!self::where('uuid', $uuid)->count()) {
                break;
            }
        }
        return $uuid;
    }

    private static function trackingNumber(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {
            $tracking = '';
            for ($i = 0; $i < 12; $i++) {
                $tracking .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (self::where('tracking_number', $tracking)->exists());

        return $tracking;
    }
}
