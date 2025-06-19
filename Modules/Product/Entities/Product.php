<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Auth\Entities\User;
use Modules\Brand\Entities\Brand;
use Modules\Category\App\Models\Category;

class Product extends Model
{
    protected $fillable = [
        'provider_id',
        'brand_id',
        'category_id',
        'created_by',
        'updated_by',
        'brand_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'purchase_price',
        'strike_price',
        'status',
        'remarks'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function  brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);

    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(ProductTag::class);
    }

    public function scopeFilter($query, $request)
    {

        return $query;
    }

    public function format($single = false): array
    {
        return [
                'created_by' => $this->createdBy?->only(['id', 'name', 'email']),
                'updated_by' => $this->updatedBy?->only(['id', 'name', 'email'])
            ] + $this->attributesToArray();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = request()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by = request()->user()->id;
        });
    }
}
