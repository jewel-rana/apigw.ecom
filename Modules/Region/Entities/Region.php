<?php

namespace Modules\Region\Entities;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Activity\App\Traits\ActivityTrait;

class Region extends Model
{
    use ActivityTrait;

    protected $fillable = ['time_zone_id', 'name', 'code', 'flag'];

    protected static $logAttributes = ['time_zone_id', 'name', 'code', 'flag'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Region {$eventName}";
    }

    public function getFlagAttribute($datetime): string
    {
        return asset('default/flags/' . strtolower($this->code) . '.png');
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }
}
