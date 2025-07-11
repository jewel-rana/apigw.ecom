<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Activity\App\Traits\ActivityTrait;

class Menu extends Model
{
    use ActivityTrait;

    protected $fillable = [
        'created_by',
        'updated_by',
        'name',
        'description',
        'wrapper_class',
        'status',
        'remarks',
    ];
    public $timestamps = false;

    protected static $logAttributes = ['name', 'description', 'wrapper_class'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Menu {$eventName}";
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id', 'id')
            ->where('parent_id', '=', 0)
            ->orderBy('menu_order', 'ASC');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->keyword . '%');
            });
        }
        return $query;
    }

    public function format($single = false)
    {
        return [
                'created_by' => $this->createdBy?->only(['id', 'name', 'email']),
                'updated_by' => $this->updatedBy?->only(['id', 'name', 'email'])
            ] + $this->only(['id', 'name', 'description', 'wrapper_class', 'status', 'remarks']);
    }

    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::deleting(function ($model) {
            $model->items()->detach();
        });

        static::created(function ($model) {
            Cache::forget('menus');
        });

        static::updated(function ($model) {
            Cache::forget('menus');
        });
    }
}
