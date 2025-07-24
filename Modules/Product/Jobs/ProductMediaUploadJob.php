<?php

namespace Modules\Product\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Media\MediaService;
use Modules\Product\Entities\Product;

class ProductMediaUploadJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private Product $product;
    private ?UploadedFile $file;
    private bool $thumb;

    public function __construct(Product $product, $file = null, $thumb = false)
    {
        $this->product = $product;
        $this->file = $file;
        $this->thumb = $thumb;
    }

    public function handle(): mixed
    {
        $file = request()->file('attachment', $this->file);
        if(is_file($file)) {
            $media = app(MediaService::class)->upload($this->file ?? request()->file('attachment'));
            if($this->thumb) {
                $this->product->update(['thumbnail' => $media->attachment]);
            } else {
                $this->product->medias()->attach($media->id);
            }
            return $media;
        }
        return null;
    }
}
