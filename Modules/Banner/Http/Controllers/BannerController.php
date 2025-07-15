<?php

namespace Modules\Banner\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Banner\Entities\Banner;
use Modules\Banner\Http\Requests\Api\StoreBannerRequest;
use Modules\Banner\Http\Requests\Api\UpdateBannerRequest;
use Modules\Banner\Jobs\BannerUploadJob;
use Modules\CMS\App\Events\BannerCacheRemoveEvent;
use Modules\Banner\Services\BannerService;
use Modules\Media\MediaService;

class BannerController extends Controller
{
    private BannerService $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function index(Request $request)
    {
        $menus = Banner::filter($request)
            ->latest()
            ->paginate(CommonHelper::perPage($request));
        return response()->success(CommonHelper::parsePaginator($menus));
    }

    public function store(StoreBannerRequest $request)
    {
        try {
            $banner = $this->bannerService->create($request->validated());
            BannerUploadJob::dispatch($banner, app(MediaService::class));

            if($request->filled('product_ids') && is_array($request->input('product_ids'))) {
                $banner->products()->sync($request->input('product_ids'));
            }
            event(new BannerCacheRemoveEvent());
            return response()->success();
        } catch (\Throwable $exception) {
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function show(Banner $banner)
    {
        return response()->success($banner->format());
    }

    public function update(UpdateBannerRequest $request, $id)
    {
        try {
            $this->bannerService->update($request->validated(), $id);
            event(new BannerCacheRemoveEvent());
            return response()->success();
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            $banner->delete();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::error($exception, [
                'keyword' => 'BANNER_DESTROY_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }
}
