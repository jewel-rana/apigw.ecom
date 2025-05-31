<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Entities\User;

class Product extends Model
{
    protected $fillable = [
        'created_by',
        'updated_by',
        'brand_id',
        'category_id',
        'name',
        'description',
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
}
