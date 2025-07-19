<?php

namespace Modules\Provider\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Product\Entities\Product;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Provider extends Authenticatable
{
    use ActivityTrait, Notifiable, HasApiTokens;

    protected $fillable = [
        'created_by',
        'name',
        'email',
        'password',
        'mobile',
        'address',
        'status',
        'updated_by'
    ];

    protected $casts = [
        'password' => 'hashed'
    ];

    protected $hidden = [
        'password',
        'updated_at',
        'deleted_at'
    ];

    protected array $logAttributes = ['name', 'email', 'password', 'status'];
    protected bool $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Provider {$eventName}";
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'provider_id', 'id');
    }

    public function providerUsers(): HasMany
    {
        return $this->hasMany(ProviderUser::class);
    }

    public function deposit(): HasOne
    {
        return $this->hasOne(ProviderDeposit::class, 'provider_id', 'id')->latest();
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(ProviderDeposit::class, 'provider_id', 'id')->latest();
    }

    public function statements(): HasMany
    {
        return $this->hasMany(ProviderStatement::class, 'id', 'provider_id');
    }

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
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('email')) {
            $query->where('email', $request->input('email'));
        }

        if ($request->filled('mobile')) {
            $query->where('mobile', $request->input('mobile'));
        }

        if ($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('keyword') . '%');
                $query->orWhere('email', 'like', '%' . $request->input('keyword') . '%');
            });
        }

        return $query;
    }

    public function format(): array
    {
        return [
                'created_by' => $this->createdBy?->only('id', 'name'),
                'updated_by' => $this->updatedBy?->only('id', 'name'),
            ] + $this->attributesToArray();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($provider) {
            $provider->created_by = auth('api')->id();
        });

        static::updating(function ($provider) {
            $provider->updated_by = auth('api')->id();
        });
    }
}
