<?php

namespace Modules\Menu\Entities;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Menu\App\Models\MenuAttribute;

class MenuItem extends Model
{
    use ActivityTrait;

    protected $fillable = ['menu_id', 'type', 'name', 'description', 'menu_url', 'css_class', 'icon_class', 'parent_id', 'menu_order'];
    protected $hidden = [
        'icon_class',
        'menu_id',
        'parent_id',
        'menu_order'
    ];
    public $timestamps = false;

    protected static $logAttributes = ['menu_id', 'type', 'name', 'description', 'menu_url', 'css_class', 'icon_class', 'parent_id', 'menu_order'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Menu item {$eventName}";
    }

    public function childs(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id', 'id')->orderBy('menu_order', 'ASC');
    }

    public function customAttributes(): HasMany
    {
        return $this->hasMany(MenuAttribute::class);
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('menu_id')) {
            $query->where('menu_id', $request->input('menu_id'));
        }

        if($request->filled('keyword')) {
            $query->where('name', 'like', "%" . $request->input('keyword') . "%");
        }
        return $query;
    }

    public function getIconAttribute()
    {
        return $this->icon_class;
    }

    public function getNameAttribute($value): ?string
    {
        return CommonHelper::parseMenuAttribute('name', $value, $this->customAttributes);
    }

    public function getDescriptionAttribute($value): ?string
    {
        return CommonHelper::parseMenuAttribute('description', $value, $this->customAttributes);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function (MenuItem $item) {
            $item->customAttributes()->delete();
        });
    }
}
