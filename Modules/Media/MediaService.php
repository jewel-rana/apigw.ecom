<?php
namespace Modules\Media;

use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Repository\MediaRepositoryInterface;

class MediaService
{
    private string $dir;
    private MediaRepositoryInterface $repository;

    public function __construct(MediaRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->dir = 'uploads/files/' . date('Y') . '/' . date('m');
    }

    public function upload($files)
    {
        if(is_array($files)) {
            foreach($files as $file) {
                return $this->handle($file);
            }
        } else {
            return $this->handle($files);
        }
    }

    public function handle($file)
    {
        if($file) {
            $imageName = time() . '.' . $file->extension();
            if(!config('media.is_cloud')) {
                $store = $file->storePubliclyAs('public/' . $this->dir . '/' .  $imageName);
                $url = 'storage/' . $this->dir . '/' . $imageName;
            } else {
                $url = Storage::disk('s3')->put($this->dir, $file);
            }
            [$width, $height] = getimagesize($file);
            return $this->repository->create([
                'original_name' => $file->getClientOriginalName(),
                'attachment' => $url,
                'extension' => 'jpg',
                'dimension' => $width . 'X' . $height,
                'size' => CommonHelper::calculateImageSize($width, $height) . 'kb',
                'ratio' => CommonHelper::calculateImageRatio($width, $height),
                'user_id' => auth()->user()->id,
                'is_cloud' => config('media.is_cloud', false)
            ]);
        }
        return null;
    }

    public function delete($media): void
    {
        $path = CommonHelper::getStoragePath($media->attachment);
        if(Storage::exists($path)) {
            Storage::delete($path);
        }
        $media->delete();
    }
}
