<?php

namespace Modules\Region\Entities;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Activity\App\Traits\ActivityTrait;

class Language extends Model
{
    use SoftDeletes, ActivityTrait;

    protected $fillable = ['name', 'code', 'status', 'is_default', 'type'];

    protected $casts = ['status' => 'boolean', 'is_default' => 'boolean'];

    protected static $logAttributes = ['name', 'code', 'status', 'is_default', 'type'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Language {$eventName}";
    }

    public function getNiceStatusAttribute(): string
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function getFlagAttribute(): string
    {
        return asset("default/languages/" . strtolower($this->code ?? 'en') . ".png");
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }

    public function format(): array
    {
        return $this->only(['name', 'code', 'flag', 'type']);
    }
}
