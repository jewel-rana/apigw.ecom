<?php

namespace Modules\Shipping\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Modules\Activity\App\Traits\ActivityTrait;

class Shipping extends Model
{
    use ActivityTrait;

    protected $fillable = [
        'created_by',
        'updated_by',
        'name',
        'code',
        'description',
        'status',
        'remarks'
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select('id', 'name', 'email');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->select('id', 'name', 'email');
    }

    public function getStatusAttribute($value): string
    {
        return ucfirst($value);
    }

    public function scopeFilter($query, $request)
    {
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'like', $request->keyword . '%');
        }

        return $query;
    }

    public function format(): array
    {
        return [
                'created_by' => $this->createdBy?->only(['id', 'name']),
                'updated_by' => $this->updatedBy?->only(['id', 'name']),
            ]
            + $this->only(['id', 'name', 'code', 'description', 'status', 'remarks', 'created_at', 'updated_at']);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::created(function ($model) {
            Cache::forget('shippings');
        });

        static::updated(function ($model) {
            Cache::forget('shippings');
        });

        static::deleted(function ($model) {
            Cache::forget('shippings');
        });
    }
}
