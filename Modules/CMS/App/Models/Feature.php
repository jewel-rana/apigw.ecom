<?php

namespace Modules\CMS\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feature extends Model
{
    protected $fillable = [
        'created_by',
        'updated_by',
        'title',
        'description',
        'remarks',
        'feature_icon',
        'type',
        'model_id',
        'position',
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

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'Active']);
    }

    public function format()
    {
        return [
                'createdBy' => $this->createdBy?->only(['id', 'name', 'email']),
                'updatedBy' => $this->updatedBy?->only(['id', 'name', 'email']),
            ] +
            $this->only([
                'id',
                'title',
                'description',
                'remarks',
                'feature_icon',
                'position',
                'type',
                'model_id',
            ]);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth('api')->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by = auth('api')->user()->id;
        });
    }
}
