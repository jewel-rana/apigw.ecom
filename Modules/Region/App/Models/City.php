<?php

namespace Modules\Region\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Region\Entities\Country;
use Modules\Region\Entities\TimeZone;

class City extends Model
{
    protected $fillable = [
        'country_id',
        'time_zone_id',
        'name',
        'code'
    ];

    protected $hidden = [
        'country_id',
        'time_zone_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function timeZone(): BelongsTo
    {
        return $this->belongsTo(TimeZone::class);
    }
}
