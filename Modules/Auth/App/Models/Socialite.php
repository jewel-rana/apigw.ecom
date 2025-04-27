<?php

namespace Modules\Auth\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Customer\App\Models\Customer;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Socialite extends Model
{
    protected $fillable = [
        'customer_id',
        'social_id',
        'provider',
        'payload',
        'access_token',
        'refresh_token',
        'revoked'
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function revoke(): bool
    {
        return self::update(['revoked' => (bool) app()->environment('production')]);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function(Socialite $socialite) {
            $socialite->uuid = self::uuid();
        });
    }

    private static function uuid(): UuidInterface
    {
        while(1) {
            $uuid = Uuid::uuid4();
            if(!self::where('uuid', $uuid)->count()) {
                break;
            }
        }
        return $uuid;
    }
}
