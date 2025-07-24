<?php

namespace Modules\Banner\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Media\Entities\Media;
use Modules\Product\Entities\Product;

class Banner extends Model
{
    use ActivityTrait;

    protected $fillable = [
        'media_id',
        'created_by',
        'updated_by',
        'title',
        'medium_text',
        'small_text',
        'position',
        'status',
        'remarks',
        'banner_url'
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    protected static $logAttributes = ['title', 'medium_text', 'small_text', 'status', 'position', 'remarks', 'created_by', 'updated_by'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Banner {$eventName}";
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function scopeFilter($query, $request)
    {
        if ($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->keyword . '%');
            });
        }
        return $query;
    }

    public function format($single = false): array
    {
        return [
                'created_by' => $this->createdBy?->only(['id', 'name']),
                'updated_by' => $this->updatedBy?->only(['id', 'name']),
                'attachment' => $this->media->attachment ?? null,
            ]
            + $this->only(['id', 'title', 'medium_text', 'small_text', 'position', 'remarks', 'banner_url', 'status', 'created_at', 'updated_at']);
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

        static::created(function ($model) {
            Cache::forget('banners');
        });

        static::updated(function ($model) {
            Cache::forget('banners');
        });

        static::deleted(function ($model) {
            Cache::forget('banners');
        });
    }
}
