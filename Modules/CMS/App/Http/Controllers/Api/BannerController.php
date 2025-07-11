<?php

namespace Modules\CMS\App\Http\Controllers\Api;

use App\Helpers\LogHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\CMS\App\Events\BannerCacheRemoveEvent;
use Modules\CMS\App\Http\Requests\StoreBannerItem;
use Modules\CMS\App\Models\Banner;
use Modules\CMS\App\Services\BannerService;

class BannerController extends Controller
{
    private BannerService $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function index()
    {
        return response()->success(
            $this->bannerService->cms()
        );
    }

    public function store(Request $request)
    {
        try {
            $this->bannerService->create($request->all());
            event(new BannerCacheRemoveEvent());
            return response()->success();
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function show(Banner $banner): View
    {
        return response()->success($banner->format());
    }

    public function update(Request $request, $id)
    {
        try {
            $this->bannerService->update($request->all(), $id);
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

    public function add(StoreBannerItem $request)
    {
        try {
            $this->bannerService->addItem($request->validated());
            return response()->success();
        } catch (\Throwable $exception) {
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function remove(Banner $banner, Request $request)
    {
        try {
            $banner->medias()->detach([$request->media_id]);
            event(new BannerCacheRemoveEvent());
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }
}
