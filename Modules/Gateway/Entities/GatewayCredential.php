<?php

namespace Modules\Gateway\Entities;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Modules\Activity\App\Traits\ActivityTrait;

class GatewayCredential extends Model
{
    use ActivityTrait;

    protected $fillable = ['gateway_id', 'key', 'value'];

    protected static $logAttributes = ['gateway_id', 'key', 'value'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Gateway credential {$eventName}";
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public function getValueAttribute($value): string
    {
        return Crypt::decrypt($value);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (GatewayCredential $credential) {
            $credential->value = Crypt::encrypt(request()->input('value'));
        });
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }
}
