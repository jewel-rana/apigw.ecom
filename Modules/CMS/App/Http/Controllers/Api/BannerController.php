<?php

namespace Modules\CMS\App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
}
