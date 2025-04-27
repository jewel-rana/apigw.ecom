<?php

namespace Modules\Category\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Category\App\Models\Category;
use Modules\Media\MediaService;

class CategoryMediaUploadJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Category $category;
    private MediaService $media;

    public function __construct(Category $category, MediaService $media)
    {
        $this->category = $category;
        $this->media = $media;
    }

    public function handle(): void
    {
        if(request()->has('attachment')) {
            $media = $this->media->upload(request()->file('attachment'));
            $this->category->medias()->detach();
            $this->category->medias()->attach($media->id);
        }
    }
}
