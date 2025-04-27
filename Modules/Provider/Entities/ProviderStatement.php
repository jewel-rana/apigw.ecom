<?php

namespace Modules\Provider\Entities;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;

class ProviderStatement extends Model
{
    protected $fillable = [];

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }
}
