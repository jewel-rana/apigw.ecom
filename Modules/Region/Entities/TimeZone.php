<?php

namespace Modules\Region\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Activity\App\Traits\ActivityTrait;

class TimeZone extends Model
{
    use ActivityTrait;

    protected $fillable = ['parent', 'name', 'time'];

    protected static $logAttributes = ['parent', 'name', 'time'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Timezone {$eventName}";
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TimeZone::class, 'parent', 'id');
    }

    public function timeZones(): HasMany
    {
        return $this->hasMany(TimeZone::class, 'parent', 'id');
    }
}
