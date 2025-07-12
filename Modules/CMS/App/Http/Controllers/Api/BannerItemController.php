<?php

namespace Modules\CMS\App\Http\Controllers\Api;

use App\Helpers\LogHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMS\App\Events\BannerCacheRemoveEvent;
use Modules\CMS\App\Http\Requests\StoreBannerItem;
use Modules\CMS\App\Http\Requests\UpdateBannerItem;
use Modules\CMS\App\Jobs\BannerUploadJob;
use Modules\CMS\App\Models\Banner;
use Modules\CMS\App\Services\BannerService;
use Modules\Media\MediaService;

class BannerItemController extends Controller
{
    private BannerService $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function index(Request $request, Banner $banner)
    {
        return response()->success($banner->format(true));
    }

    public function store(StoreBannerItem $request, Banner $banner)
    {
        try {
            $this->bannerService->addItem($request->validated());
            return response()->success();
        } catch (\Throwable $exception) {
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function show(Banner $banner, $item)
    {
        return response()->success($banner->medias->where('id', $item)->first());
    }

    public function update(UpdateBannerItem $request, Banner $banner, $item)
    {
        try {
            BannerUploadJob::dispatch($request->validated(), app(MediaService::class), $item);
            event(new BannerCacheRemoveEvent());
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'BANNER_ITEM_UPDATE_EXCEPTION'
            ]);
            return response()->error($exception->getMessage());
        }
    }

    public function destroy(Banner $banner, $item)
    {
        try {
            $banner->medias()->detach([$item]);
            return response()->success();
        } catch (\Throwable $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'BANNER_ITEM_DELETE_EXCEPTION'
            ]);
            return response()->failed();
        }
    }
}
