<?php

namespace Modules\CMS\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Media\Entities\Media;

class Banner extends Model
{
    use ActivityTrait;

    protected $fillable = [
        'created_by',
        'updated_by',
        'name',
        'label',
        'is_default',
        'status',
        'remarks'
    ];
    protected $casts = ['status' => 'bool', 'is_default' => 'bool'];

    protected static $logAttributes = ['name', 'label', 'is_default', 'status'];
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

    public function medias(): BelongsToMany
    {
        return $this->belongsToMany(Media::class)
            ->withPivot(['id', 'title', 'slogan', 'description', 'text_size', 'text_color', 'btn_color', 'btn_text', 'btn_url']);
    }

    public function getNiceStatusAttribute(): string
    {
        return 'Active';
    }

    public function getMediaAttachmentUrlAttribute(): string
    {
        $media = $this->media();
        $url = asset('default/category.png');
        if ($media) {
            $url = $media->is_cloud ? Storage::disk('s3')->url($media->attachment) : asset($media->attachment);
        }
        return $url;
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

    public function format(): array
    {
        return [
                'created_by' => $this->createdBy?->only(['id', 'name']),
                'updated_by' => $this->updatedBy?->only(['id', 'name']),
            ]
            + $this->only(['id', 'name', 'remarks', 'is_default', 'status']) +
            [
                'medias' => $this->medias->map(function ($item) {
                    return $item->only(['pivot']) +
                        [
                            'attachment' => $item->attachment
                        ];
                })
            ];
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
    }
}
