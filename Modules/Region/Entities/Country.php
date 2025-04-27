<?php

namespace Modules\Region\Entities;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Activity\App\Traits\ActivityTrait;

class Country extends Model
{
    use ActivityTrait;

    protected $fillable = ['name', 'code', 'currency_id', 'zone_id', 'time_zone_id'];

    protected $hidden = [
        'zone_id',
        'currency_id',
        'time_zone_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static $logAttributes = ['name', 'code', 'currency_id', 'zone_id', 'time_zone_id'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Country {$eventName}";
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(TimeZone::class, 'zone_id', 'id');
    }

    public function timeZone(): BelongsTo
    {
        return $this->belongsTo(TimeZone::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }
}
