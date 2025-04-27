<?php

namespace Modules\Region\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Operator\Entities\Operator;
use Modules\Region\Entities\Region;
use Modules\Region\Entities\TimeZone;
use Modules\Region\Http\Requests\RegionCreateRequest;
use Modules\Region\Http\Requests\RegionUpdateRequest;
use Modules\Region\Services\CountryService;
use Modules\Region\Services\RegionService;

class RegionController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    private RegionService $regionService;
    private CountryService $countryService;

    public function __construct(
        CountryService $countryService,
        RegionService $regionService
    )
    {
        $this->countryService = $countryService;
        $this->regionService = $regionService;
    }

    public function index(Request $request)
    {
        if($request->acceptsJson() && $request->wantsJson()) {
            return $this->regionService->getDataTables($request);
        }
        return view('region::index')->with(['title' => 'Regions']);
    }

    public function create()
    {
        $countries = $this->countryService->all();
        $timezone = null;
        if(old('time_zone_id')) {
            $timezone = TimeZone::find(old('time_zone_id'));
        }
        return view('region::create', compact('countries', 'timezone'))->with(['title' => 'Add new region']);
    }

    public function store(RegionCreateRequest $request): RedirectResponse
    {
        return $this->regionService->create($request);
    }

    public function show(Region $region)
    {
        return view('region::show')->with(['title' => $region->name]);
    }

    public function edit(Region $region)
    {
        $countries = $this->countryService->all();
        $timezone = null;
        if(old('time_zone_id', $region->time_zone_id)) {
            $timezone = TimeZone::find(old('time_zone_id', $region->time_zone_id));
        }
        return view('region::edit', compact('countries', 'region', 'timezone'))->with(['title' => 'Update region']);
    }

    public function update(RegionUpdateRequest $request, $id): RedirectResponse
    {
        return $this->regionService->update($request, $id);
    }

    public function destroy($id)
    {
        //
    }

    public function suggestion(Request $request): JsonResponse
    {
        return $this->regionService->suggestion($request);
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['suggestions', 'delete'])) {
            $this->authorize($method, Region::class);
        }
        return parent::callAction($method, $parameters);
    }
}
