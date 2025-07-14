<?php

namespace Modules\CMS\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeCard extends Model
{
    protected $fillable = [
        'created_by',
        'updated_by',
        'title',
        'description',
        'icon',
        'bg_color',
        'text_color',
        'border_color',
        'variant_color',
        'position',
        'url',
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

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'Active']);
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('status') && in_array($request->status, ['Active', 'Inactive'])) {
            $query->where('status', $request->status);
        }
        return $query;
    }

    public function format(): array
    {
        return [
                'created_by' => $this->createdBy?->only(['id', 'name', 'email']),
                'updated_by' => $this->updatedBy?->only(['id', 'name', 'email']),
            ] +
            $this->toArray();
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }
}
