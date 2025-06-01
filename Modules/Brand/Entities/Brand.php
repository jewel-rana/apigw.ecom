<?php

namespace Modules\Brand\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Modules\Activity\App\Traits\ActivityTrait;

class Brand extends Model
{
    use ActivityTrait;

    protected $fillable = [
        'created_by',
        'updated_by',
        'name',
        'slug',
        'description',
        'image',
        'status',
        'remarks'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function scopeFilter($query, $request)
    {
        return $query;
    }

    public function format()
    {
        return [
                'createdBy' => $this->createdBy?->only(['id', 'name', 'email']),
                'updatedBy' => $this->updatedBy?->only(['id', 'name', 'email']),
        ] + $this->attributesToArray();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (Brand $brand) {
            $brand->created_by = auth()->id();
            $brand->slug = Str::slug($brand->slug ?? $brand->name);
        });

        static::updating(function (Brand $brand) {
            $brand->updated_by = auth()->id();
        });
    }
}
