<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Modules\CMS\App\Events\BannerCacheRemoveEvent;
use Modules\CMS\App\Http\Requests\StoreBannerItem;
use Modules\CMS\App\Models\Banner;
use Modules\CMS\App\Services\BannerService;
use Modules\Menu\Entities\Menu;

class BannerController extends Controller
{
    private BannerService $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->bannerService->getDataTable($request);
        }
        return view('cms::banner.index');
    }

    public function create(): View
    {
        return view('cms::banner.create');
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->bannerService->create($request->all());
    }

    public function show(Banner $banner): View
    {
        return view('cms::banner.show', compact('banner'));
    }

    public function edit(Banner $banner): View
    {
        return view('cms::banner.edit', compact('banner'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        return $this->bannerService->update($request->all(), $id);
    }

    public function destroy($id)
    {
        //
    }

    public function add(StoreBannerItem $request): RedirectResponse
    {
        try {
            $this->bannerService->addItem($request->validated());
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
        }

        return redirect()->back();
    }

    public function remove(Banner $banner, Request $request): RedirectResponse
    {
        try {
            $banner->medias()->detach([$request->media_id]);
            event(new BannerCacheRemoveEvent());
            return redirect()->back()->with(['status' => true, 'message' => 'Successfully deleted']);
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return redirect()->back()->with(['status' => true, 'message' => 'Failed to delete']);
        }
    }
}
