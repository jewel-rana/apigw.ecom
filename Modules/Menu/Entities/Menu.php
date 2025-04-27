<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Activity\App\Traits\ActivityTrait;

class Menu extends Model
{
    use ActivityTrait;

    protected $fillable = ['name', 'description', 'wrapper_class'];
    public $timestamps = false;

    protected static $logAttributes = ['name', 'description', 'wrapper_class'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Menu {$eventName}";
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id', 'id')->where('parent_id', '=', 0)->orderBy('menu_order', 'ASC');
    }
}
