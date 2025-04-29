<?php

namespace Modules\Provider\Entities;

use App\Helpers\CommonHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Bundle\Entities\Bundle;
use Modules\Operator\Entities\Operator;

class Provider extends Model
{
    use ActivityTrait;
    protected $fillable = ['user_id', 'name', 'email', 'password', 'status', 'updated_by'];

    protected $hidden = [
        'password',
        'updated_at',
        'deleted_at'
    ];

    protected $logAttributes = ['name', 'email', 'password', 'status'];
    protected $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Provider {$eventName}";
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

    public function operators(): BelongsToMany
    {
        return $this->belongsToMany(Operator::class)->withPivot('user_id');
    }

    public function bundles(): BelongsToMany
    {
        return $this->belongsToMany(Bundle::class);
    }

    public function statements(): HasMany
    {
        return $this->hasMany(ProviderStatement::class, 'id', 'provider_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name', 'email', 'mobile')->withTrashed();
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->select('id', 'name', 'email', 'mobile')->withTrashed();
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }

    public function getUpdatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }

    public function getNiceStatusAttribute($value): string
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('keyword') . '%');
                $query->orWhere('email', 'like', '%' . $request->input('keyword') . '%');
            });
        }

        return $query;
    }

    public function format(): array
    {
        return $this->toArray();
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function ($provider) {
            $provider->updated_by = auth()->id();
        });
    }
}
