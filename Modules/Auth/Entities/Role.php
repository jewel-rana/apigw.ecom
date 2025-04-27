<?php

namespace Modules\Auth\Entities;

use App\Helpers\CommonHelper;
use Modules\Activity\App\Traits\ActivityTrait;

class Role extends \Spatie\Permission\Models\Role
{
    use ActivityTrait;

    protected $fillable = ['name', 'guard_name'];

    public $guard_name = 'web';

    protected static $logAttributes = ['name', 'guard_name'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Role {$eventName}";
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }
}
