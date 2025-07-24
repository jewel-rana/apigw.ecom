<?php

namespace Modules\Gateway\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Activity\App\Traits\ActivityTrait;

class Gateway extends Model
{
    use SoftDeletes, ActivityTrait;

    const ACTIVE = 'Active';
    const INACTIVE = 'Inactive';
    const ACTIVE_TEXT = 'Active';
    const INACTIVE_TEXT = 'Inactive';

    protected $fillable = [
        'name',
        'class_name',
        'status',
        'is_editable'
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    protected static $logAttributes = ['name', 'class_name', 'status', 'is_editable'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Gateway {$eventName}";
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')
            ->select('id', 'name', 'email');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')
            ->select('id', 'name', 'email');
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(GatewayCredential::class);
    }

    public function endpoints(): HasMany
    {
        return $this->hasMany(GatewayEndpoint::class);
    }

    public function scopeFilter($query, $request)
    {
        return $query;
    }

    public function format(): array
    {
        return [
                'created_by' => $this->createdBy?->only(['id', 'name', 'email']),
                'updated_by' => $this->updatedBy?->only(['id', 'name', 'email']),
            ] + $this->only(['id', 'name', 'class_name', 'status', 'created_at', 'updated_at', 'is_editable']);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth('api')->id() ?? 1;
        });

        static::updating(function ($model) {
            $model->updated_by = auth('api')->id() ?? 1;
        });
    }
}
