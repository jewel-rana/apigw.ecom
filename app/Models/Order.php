<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'promotion',
        'promotion_objective',
        'promotion_period',
        'gender',
        'min_age',
        'max_age',
        'amount',
        'location',
        'divisions',
        'status',
        'created_by',
        'updated_by',
        'remarks',
        'note'
    ];

//    protected $casts = [
//        'divisions' => 'json'
//    ];

    protected $hidden = [
        'deleted_at'
    ];

//    protected $attributes = [
//        'divisions' => []
//    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->select('id', 'name', 'email', 'mobile');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latest()->select('id', 'status', 'payment_method', 'gateway_trx_id', 'gateway_payment_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select('id', 'name', 'email');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->select('id', 'name', 'email');
    }

    public function objectives(): HasMany
    {
        return $this->hasMany(OrderAttribute::class);
    }

    public function scopeFilter($query, Request $request)
    {
        if(request()->user()->type == 'customer') {
            $query->where('customer_id', request()->user()->id);
        }
        return CommonHelper::filterModel($query, $request);
    }

    public function getColorAttribute()
    {
        return config('colors.order')[strtolower($this->status)] ?? "gray";
    }

    public function format(): array
    {
        return $this->only(['id', 'promotion', 'promotion_objective', 'promotion_period', 'amount', 'location', 'gender', 'min_age', 'max_age', 'status', 'remarks', 'note', 'created_at', 'updated_at']) +
            [
                'divisions' => json_decode($this->divisions),
                'created_by' => $this->createdBy,
                'updated_by' => $this->updatedBy,
                'objectives' => $this->objectives->map(function(OrderAttribute $item) {
                    return $item->only(['key', 'value']);
                }),
                'customer' => $this->customer->only(['id', 'name', 'email', 'mobile']),
                'payment' => $this->payment
            ];
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function(Order $order) {
            $order->divisions = json_encode($order->divisions);
            $order->invoice_no = Str::random(16);
            if(Auth::user()->type == 'admin') {
                $order->created_by = request()->user()->id;
            }

            if(request()->user()->type == 'customer') {
                $order->customer_id = request()->user()->id ?? request()->input('customer_id');
            }
        });

        static::updating(function(Order $order) {
            if(request()->user()->type == 'admin') {
                $order->updated_by = request()->user()->id;
            }
        });
    }
}
