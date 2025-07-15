<?php

namespace Modules\Banner\Jobs;

use App\Helpers\LogHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Modules\Banner\Entities\Banner;
use Modules\Media\MediaService;

class BannerUploadJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private MediaService $media;
    private Banner $banner;

    public function __construct(Banner $banner, MediaService $mediaService)
    {
        $this->media = $mediaService;
        $this->banner = $banner;
    }

    public function handle(): void
    {
        try {
            $media = null;
            if (request()->has('attachment')) {
                $media = $this->media->upload(request()->file('attachment'));
            }

            if ($media) {
                $this->banner->update(['media_id' => $media->id]);
            }

            Cache::forget('banners');
            Cache::forget('api_banners');
        } catch (\Exception $exception) {
            dd($exception);
            LogHelper::exception($exception);
        }
    }
}
