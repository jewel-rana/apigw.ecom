<?php

namespace Modules\Region\Entities;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Modules\Activity\App\Traits\ActivityTrait;

class Currency extends Model
{
    use ActivityTrait;

    protected $fillable = ['name', 'code', 'symbol'];

    protected static $logAttributes = ['name', 'code', 'symbol'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Currency {$eventName}";
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }
}
