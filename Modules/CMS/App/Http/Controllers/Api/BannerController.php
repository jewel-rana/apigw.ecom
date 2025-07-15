<?php

namespace Modules\CMS\App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Banner\Services\BannerService;

class BannerController extends Controller
{
    private BannerService $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function cms()
    {
        return response()->success(
            $this->bannerService->cms()
        );
    }
}
