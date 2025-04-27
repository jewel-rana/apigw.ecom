<?php

namespace Modules\Region\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Operator\Entities\Operator;
use Modules\Region\App\Models\City;
use Modules\Region\Entities\Region;
use Modules\Region\Entities\TimeZone;
use Modules\Region\Http\Requests\RegionCreateRequest;
use Modules\Region\Http\Requests\RegionUpdateRequest;
use Modules\Region\Http\Requests\StoreCityRequest;
use Modules\Region\Http\Requests\UpdateCityRequest;
use Modules\Region\Services\CityService;
use Modules\Region\Services\CountryService;

class CityController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    private CityService $cityService;
    private CountryService $countryService;

    public function __construct(
        CountryService $countryService,
        CityService $cityService
    )
    {
        $this->countryService = $countryService;
        $this->cityService = $cityService;
    }

    public function index(Request $request)
    {
        if($request->acceptsJson() && $request->wantsJson()) {
            return $this->cityService->getDataTables($request);
        }
        return view('region::city.index')->with(['title' => 'Cities']);
    }

    public function create()
    {
        $countries = $this->countryService->all();
        $timezone = null;
        if(old('time_zone_id')) {
            $timezone = TimeZone::find(old('time_zone_id'));
        }
        return view('region::city.create', compact('countries', 'timezone'))->with(['title' => 'Add new city']);
    }

    public function store(StoreCityRequest $request): RedirectResponse
    {
        return $this->cityService->create($request);
    }

    public function show(Region $region)
    {
        return view('region::city.show')->with(['title' => $region->name]);
    }

    public function edit(City $city)
    {
        $countries = $this->countryService->all();
        $timezone = null;
        if(old('time_zone_id', $city->time_zone_id)) {
            $timezone = TimeZone::find(old('time_zone_id', $city->time_zone_id));
        }
        return view('region::city.edit', compact('countries', 'city', 'timezone'))->with(['title' => 'Update city']);
    }

    public function update(UpdateCityRequest $request, $id): RedirectResponse
    {
        return $this->cityService->update($request, $id);
    }

    public function destroy($id)
    {
        //
    }

    public function suggestion(Request $request): JsonResponse
    {
        return $this->cityService->suggestion($request);
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['suggestion', 'delete'])) {
            $this->authorize($method, Region::class);
        }
        return parent::callAction($method, $parameters);
    }
}
