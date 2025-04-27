<?php

namespace Modules\Category\App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Activity\App\Traits\ActivityTrait;

class CategoryAttribute extends Model
{
    use ActivityTrait;

    protected $fillable = ['category_id', 'lang', 'key', 'value'];

    protected $hidden = [
        'id',
        'category_id',
        'lang',
        'created_at',
        'updated_at'
    ];

    protected static $logAttributes = ['category_id', 'lang', 'key', 'value'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Category attribute {$eventName}";
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }
}
