<?php

namespace Modules\Setting\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Activity\App\Traits\ActivityTrait;

class OptionAttribute extends Model
{
    use ActivityTrait;

    protected $fillable = ['key', 'lang',  'value'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected static $logAttributes = ['key', 'lang', 'value'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Option attribute {$eventName}";
    }
}
