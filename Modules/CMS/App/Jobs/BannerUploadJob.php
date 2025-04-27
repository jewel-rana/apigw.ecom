<?php

namespace Modules\CMS\App\Jobs;

use App\Helpers\LogHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Modules\CMS\App\Models\Banner;
use Modules\Media\MediaService;

class BannerUploadJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private MediaService $media;
    private array $data;
    private ?int $itemId;

    public function __construct(array $data, MediaService $mediaService, $itemId = null)
    {
        $this->media = $mediaService;
        $this->data = $data;
        $this->itemId = $itemId;
    }

    public function handle(): void
    {
        try {
            $media = null;
            if (request()->has('attachment')) {
                $media = $this->media->upload(request()->file('attachment'));
            }
            if (!$this->itemId && $media) {
                Banner::find($this->data['banner_id'])->medias()->attach($media->id,
                    [
                        'title' => $this->data['title'],
                        'slogan' => $this->data['slogan'],
                        'description' => $this->data['description'],
                        'text_size' => $this->data['text_size'] ?? 'large',
                        'text_color' => $this->data['text_color'],
                        'btn_color' => $this->data['btn_color'],
                        'btn_text' => $this->data['btn_text'],
                        'btn_url' => $this->data['btn_url']
                    ]
                );
            }

            if ($this->itemId) {
                $data = Arr::except($this->data, ['attachment', 'banner_id', '_token', '_method']);
                if ($media) {
                    $data['media_id'] = $media->media_id;;
                }
                $banner = Banner::find($this->data['banner_id']);

                $banner->medias()
                    ->updateExistingPivot(
                        $this->itemId,
                        $data
                    );
            }

            Cache::forget('banners');
            Cache::forget('api_banners');
        } catch (\Exception $exception) {
            dd($exception);
            LogHelper::exception($exception);
        }
    }
}
