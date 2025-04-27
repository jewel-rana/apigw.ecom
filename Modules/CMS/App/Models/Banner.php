<?php

namespace Modules\CMS\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\CMS\Database\factories\BannerFactory;
use Modules\Media\Entities\Media;

class Banner extends Model
{
    use ActivityTrait;
    protected $fillable = ['name', 'label', 'is_default', 'status'];
    protected $casts = ['status' => 'bool', 'is_default' => 'bool'];

    protected static $logAttributes = ['name', 'label', 'is_default', 'status'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Banner {$eventName}";
    }

    public function medias(): BelongsToMany
    {
        return $this->belongsToMany(Media::class)
            ->withPivot(['id', 'title', 'slogan', 'description', 'text_size', 'text_color', 'btn_color', 'btn_text', 'btn_url']);
    }

    public function getNiceStatusAttribute(): string
    {
        return 'Active';
    }

    public function getMediaAttachmentUrlAttribute(): string
    {
        $media = $this->media();
        $url = asset('default/category.png');
        if($media) {
            $url = $media->is_cloud ? Storage::disk('s3')->url($media->attachment) : asset($media->attachment);
        }
        return $url;
    }

    public function format(): array
    {
        return $this->only(['id', 'name']);
    }
}
