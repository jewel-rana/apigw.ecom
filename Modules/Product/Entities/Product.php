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
use Modules\Provider\Entities\Provider;

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
        'is_featured',
        'badge'
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

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
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
        if ($request->filled('supplier_id')) {
            $query->where('provider_id', $request->input('supplier_id'));
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('from')) {
            $query->where('created_at', '>=', $request->input('from') . ' 00:00:00');
        }

        if ($request->filled('to')) {
            $query->where('created_at', '<=', $request->input('to') . ' 23:59:59');
        }

        if ($request->filled('status') && in_array(strtolower($request->input('status')), ['pending', 'active', 'inactive', 'publish', 'failed', 'complete', 'refunded'])) {
            $query->where('status', '=', ucfirst($request->input('status')));
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', '=', $request->input('created_by'));
        }

        if ($request->filled('updated_by')) {
            $query->where('updated_by', '=', $request->input('updated_by'));
        }

        if ($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('title', 'like', "%" . $request->input('keyword') . "%");
            });
        }
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
                'supplier_id' => $this->provider_id,
                'supplier' => $this->provider?->only(['id', 'name']),
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
                'badge',
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
