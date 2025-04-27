<?php

namespace Modules\Gateway\Entities;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Activity\App\Traits\ActivityTrait;

class GatewayEndpoint extends Model
{
    use SoftDeletes, ActivityTrait;

    protected $fillable = ['gateway_id', 'key', 'value'];

    protected static $logAttributes = ['gateway_id', 'key', 'value'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Gateway endpoint {$eventName}";
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }
}
