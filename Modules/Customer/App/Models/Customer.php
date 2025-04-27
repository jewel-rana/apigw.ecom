<?php

namespace Modules\Customer\App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Auth\App\Models\Socialite;
use Modules\Bundle\Entities\Bundle;
use Modules\Operator\Entities\Operator;
use Modules\Order\App\Models\Order;
use Modules\Region\App\Models\City;
use Modules\Region\Entities\Country;

class Customer extends Authenticatable
{
    use HasApiTokens, Notifiable, ActivityTrait;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'country_id',
        'city_id',
        'code',
        'address',
        'gender',
        'status',
        'email_verified_at'
    ];

    protected $casts = [
        'password' => 'hashed',
        'created_at' => 'datetime:d/m/Y h:i a',
        'updated_at' => 'datetime:d/m/Y h:i a',
    ];

    protected $hidden = [
        'city_id',
        'country_id',
        'updated_at',
        'password',
        'email_verified_at'
    ];

    protected static $logAttributes = [
        'name',
        'email',
        'mobile',
        'password',
        'country_id',
        'city_id',
        'code',
        'address',
        'gender',
        'status',
        'email_verified_at'
    ];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Customer {$eventName}";
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function socialites(): HasMany
    {
        return $this->hasMany(Socialite::class);
    }

    public function wishlistBundles(): BelongsToMany
    {
        return $this->belongsToMany(Bundle::class);
    }

    public function wishlistOperators(): BelongsToMany
    {
        return $this->belongsToMany(Operator::class);
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('date_from')) {
            $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('date_from'));
            $query->where('created_at', '>=', $dateFrom->startOfDay());
        }

        if($request->filled('date_to')) {
            $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('date_to'));
            $query->where('created_at', '<=', $dateTo->endOfDay());
        }

        if($request->filled('country_id')) {
            $query->where('country_id', $request->input('country_id'));
        }

        if($request->filled('city_id')) {
            $query->where('city_id', $request->input('city_id'));
        }

        if($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        if($request->filled('keyword')) {
            $query->where(function($query) use ($request) {
                $query->where('name', 'like', "%{$request->input('keyword')}%");
                $query->orWhere('email', 'like', "%{$request->input('keyword')}%");
                $query->orWhere('mobile', 'like', "%{$request->input('keyword')}%");
            });
        }

        return $query;
    }

    public function format(): array
    {
        return $this->attributesToArray() +
            [
                'country' => $this->country?->only(['id', 'name']),
                'city' => $this->city?->only(['id', 'name'])
            ];
    }

    public static function boot(): void
    {
        parent::boot();
        static::deleting(function ($customer) {
            $customer->orders()->each(function ($order) {
                $order->delete();
            });
        });
    }
}
