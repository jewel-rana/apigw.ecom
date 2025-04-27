<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Setting\App\Models\OptionAttribute;

class Option extends Model
{
    use ActivityTrait;

    protected $fillable = ['field', 'value', 'tab'];
    public $timestamps = false;

    protected static $logAttributes = ['field', 'value', 'tab'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Option {$eventName}";
    }

    public function customAttributes(): HasMany
    {
        return $this->hasMany(OptionAttribute::class, 'key', 'field');
    }

    public function getValueAttribute($value)
    {
        if(app()->getLocale() != 'en') {
            $attributes = $this->customAttributes->where('lang', app()->getLocale());
            if($attributes->count()) {
                $value = $attributes->first()->value;
            }
        }
        return $value;
    }
}
