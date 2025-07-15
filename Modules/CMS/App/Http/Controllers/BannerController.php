<?php

namespace Modules\CMS\App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Banner\Services\BannerService;
use Modules\Banner\Entities\Banner;

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
}
