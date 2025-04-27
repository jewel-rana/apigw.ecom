<?php

namespace Modules\Media\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Modules\Brand\Entities\Brand;

class Media extends Model
{
    protected $table = 'medias';
    protected $fillable = [
        'original_name',
        'attachment',
        'type',
        'extension',
        'size',
        'dimension',
        'ratio',
        'user_id',
        'is_cloud'
    ];

    protected $casts = ['is_cloud' => 'bool'];
    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
        'is_cloud'
    ];

    public function getAttachmentAttribute($media): string
    {
        $url = asset('default/banner.png');
        if($media) {
            $url = $this->is_cloud ? Storage::disk('s3')->url($media) : asset($media);
        }
        return $url;
    }

    public function format(): array
    {
        return [$this->ratio => $this->attachment];
    }
}
