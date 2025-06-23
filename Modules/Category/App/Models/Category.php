<?php

namespace Modules\Category\App\Models;

use App\Helpers\CommonHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Bundle\Entities\Bundle;
use Modules\Media\Entities\Media;
use Modules\Operator\Entities\Operator;
use Modules\ServiceType\Entities\ServiceType;

class Category extends Model
{
    use SoftDeletes, ActivityTrait;

    protected $fillable = [
        'service_type_id',
        'name',
        'icon',
        'parent_id',
        'color',
        'type',
        'icon',
        'position',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'service_type_id',
        'parent_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'medias',
        'custom_attributes'
    ];

    public static array $defaultKeys = [
        'name'
    ];

    protected static $logAttributes = ['service_type_id', 'name', 'parent_id'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Category {$eventName}";
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select('id', 'name', 'email');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->select('id', 'name', 'email');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function childs(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function operators(): HasMany
    {
        return $this->hasMany(Operator::class, 'category_id', 'id');
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Bundle::class, Operator::class, 'category_id', 'id', 'id', 'id')
            ->orderByDesc('is_featured')
            ->latest();
    }

    public function medias(): BelongsToMany
    {
        return $this->belongsToMany(Media::class)->latest();
    }

    public function media()
    {
        return $this->medias->first();
    }

    public function customAttributes(): HasMany
    {
        return $this->hasMany(CategoryAttribute::class)->where('lang', app()->getLocale());
    }

    public function getNameAttribute($value): ?string
    {
        return CommonHelper::parseLocalizeAttribute('name', $value, $this->customAttributes);
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }

    public function getMediaAttachmentUrlAttribute(): string
    {
        return $this->media()->attachment ?? asset('default/category.png');
    }

    public function getIconAttribute($value): string
    {
        return $value ? asset($value) : asset('default/category.png');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('position')) {
            $query->where('position', $request->input('position'));
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->input('keyword') . '%');
        }

        return $query;
    }

    public function format($single = false): array
    {
        $data = $this->only(['id', 'name', 'slug', 'color', 'position', 'remarks']) +
            [
                'icon' => $this->icon ?? '',
                'created_by' => $this->createdBy?->only(['id', 'name', 'email']),
                'updated_by' => $this->updatedBy?->only(['id', 'name', 'email'])
            ];

        if ($single) {
            $data['children'] = $this->childs;
        }
        return $data;
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function (Category $category) {
            $category->parent_id = (request()->filled('parent_id')) ? request()->input('parent_id') : null;
            $category->created_by = auth()->id();
            $category->slug = Str::slug($category->slug ?? $category->name);
        });

        static::updating(function (Category $category) {
            $category->updated_by = auth()->id();
            $category->parent_id = (request()->filled('parent_id')) ? request()->input('parent_id') : null;
        });
    }
}
