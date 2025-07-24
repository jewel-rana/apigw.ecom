<?php

namespace Modules\Product\Entities;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Modules\Auth\Entities\User;
use Modules\Brand\Entities\Brand;
use Modules\Category\App\Models\Category;
use Modules\Media\Entities\Media;

class Product extends Model
{
    protected $fillable = [
        'provider_id',
        'brand_id',
        'category_id',
        'created_by',
        'updated_by',
        'brand_id',
        'category_id',
        'title',
        'slug',
        'sku',
        'description',
        'price',
        'purchase_price',
        'strike_price',
        'status',
        'remarks',
        'thumbnail',
        'weight',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'strike_price' => 'decimal:2',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);

    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(ProductTag::class);
    }

    public function medias(): BelongsToMany
    {
        return $this->belongsToMany(Media::class);
    }

    public function scopeFilter($query, $request)
    {
        $query = CommonHelper::filterModel($query, $request);
        return $query;
    }

    public function getThumbnailAttribute($value): ?string
    {
        return ($value) ? asset($value) : null;
    }

    public function getStatusAttribute($value): ?string
    {
        return ucfirst($value);
    }

    public function format($single = false): array
    {
        $data = [
                'created_by' => $this->createdBy?->only(['id', 'name', 'email']),
                'updated_by' => $this->updatedBy?->only(['id', 'name', 'email']),
                'category' => $this->category?->only(['id', 'name']),
                'brand' => $this->brand?->only(['id', 'name']),
            ] + $this->only([
                'id',
                'title',
                'slug',
                'sku',
                'description',
                'price',
                'purchase_price',
                'strike_price',
                'status',
                'remarks',
                'thumbnail',
                'is_featured',
                'weight',
                'created_at',
                'updated_at'
            ]);

        if ($single) {
            $data['medias'] = $this->medias->map(function ($media) {
                return $media->only(['id', 'attachment']);
            });
        }

        return $data;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = auth('api')->user();
            $provider = auth('supplier')->user();
            if($provider) {
                $model->provider_id = $provider->id;
            } else {
                $model->provider_id = request()->input('supplier_id') ?? null;
            }
            $model->created_by = $user->id;
            $model->slug = Str::slug(strtolower($model->title));
        });

        static::updating(function ($model) {
            $user = auth('api')->user();
            $model->provider_id = request()->input('supplier_id', $model->provider_id ?? 1);
            $model->updated_by = $user->id;
            $model->slug = Str::slug(strtolower($model->title));
        });
    }
}
