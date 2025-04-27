<?php

namespace Modules\Region\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Region\Services\CountryService;
use Modules\Region\Services\RegionService;

class RegionController extends Controller
{
    private RegionService $regionService;
    private CountryService $countryService;

    public function __construct(
        CountryService $countryService,
        RegionService  $regionService
    )
    {
        $this->countryService = $countryService;
        $this->regionService = $regionService;
    }

    public function country(Request $request)
    {
        return response()->success(
            $this->countryService->getCountries($request)
        );
    }

    public function city(Request $request)
    {
        return response()->success(
            $this->regionService->getCities($request)
        );
    }

    public function region(Request $request)
    {
        return response()->success(
            $this->regionService->getRegions($request)
        );
    }
}
