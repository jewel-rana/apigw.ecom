<?php

namespace Modules\Menu\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Menu\Entities\MenuItem;

class MenuAttribute extends Model
{
    use ActivityTrait;

    protected $fillable = ['menu_id', 'menu_item_id', 'language', 'name', 'description'];

    protected $hidden = [
        'menu_id',
        'menu_item_id'
    ];

    protected static $logAttributes = ['menu_id', 'menu_item_id', 'language', 'name', 'description'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Menu attribute {$eventName}";
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('menu_id')) {
            $query->where('menu_id', $request->input('menu_id'));
        }

        if($request->filled('menu_item_id')) {
            $query->where('menu_item_id', $request->input('menu_item_id'));
        }

        if($request->filled('keyword')) {
            $query->whereHas('menuItem', function($query) use ($request) {
                $query->where('name', 'like', "%" . $request->input('keyword') . "%");
            });
        }
        return $query;
    }

    public function format(): array
    {
        return $this->only(['id', 'menu_id', 'menu_item_id', 'language', 'name', 'description']) +
            [
                'item' => $this->menuItem->only('id', 'name')
            ];
    }
}
