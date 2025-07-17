<?php

namespace Modules\CMS\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Product\Entities\Product;

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
        'status'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('position');
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('status') && in_array($request->status, ['Active', 'Inactive'])) {
            $query->where('status', $request->status);
        }
        return $query;
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Active', 'active']);
    }

    public function format(): array
    {
        return [
                'created_by' => $this->createdBy?->only(['id', 'name', 'email']),
                'updated_by' => $this->updatedBy?->only(['id', 'name', 'email']),
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
                'status'
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
