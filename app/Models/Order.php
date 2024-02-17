<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
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
        'remarks'
    ];

    protected $casts = [
        'divisions' => 'array'
    ];

    protected $hidden = [
        'deleted_at'
    ];

//    protected $attributes = [
//        'divisions' => ''
//    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->select('id', 'name', 'email');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function objectives(): HasMany
    {
        return $this->hasMany(OrderAttribute::class);
    }

    public function scopeFilter($query, Request $request)
    {
        return CommonHelper::filterModel($query, $request);
    }

    public function format(): array
    {
        return $this->only(['id', 'invoice_no', 'promotion', 'promotion_objective', 'promotion_period', 'amount', 'location', 'divisions', 'gender', 'min_age', 'max_age', 'status', 'remarks']) +
            [
                'created_by' => $this->createdBy,
                'updated_by' => $this->updatedBy,
                'objectives' => $this->objectives->map(function(OrderAttribute $item) {
                    return $item->only(['key', 'value']);
                })
            ];
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function(Order $order) {
            $order->divisions = json_encode($order->divisions);
            $order->invoice_no = Str::random(16);
        });
    }
}
