<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Customer\App\Models\Customer;

class ProductWishList extends Model
{
    protected $fillable = [
        'product_id',
        'customer_id'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeFilter($query, $request)
    {
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->get('product_id'));
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->get('customer_id'));
        }

        return $query;
    }

    public function format(): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'customer_id' => $this->customer_id,
            'product' => $this->product ? $this->product->format() : null,
        ];
    }

    public static function boot()
    {
        parent::boot();
    }
}
