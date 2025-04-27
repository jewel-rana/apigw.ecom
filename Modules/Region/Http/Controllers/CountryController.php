<?php

namespace Modules\Region\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Operator\Entities\Operator;
use Modules\Region\Entities\Country;
use Modules\Region\Entities\Currency;
use Modules\Region\Entities\TimeZone;
use Modules\Region\Http\Requests\CountryCreateRequest;
use Modules\Region\Http\Requests\CountryUpdateRequest;
use Modules\Region\Services\CountryService;
use Modules\Region\Services\TimeZoneService;

class CountryController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    private CountryService $countryService;
    private TimeZoneService $timeZoneService;

    public function __construct(
        TimeZoneService $timeZoneService,
        CountryService $countryService
    )
    {
        $this->timeZoneService = $timeZoneService;
        $this->countryService = $countryService;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->countryService->getDataTables($request);
        }
        return view('region::country.index')->with(['title' => 'Countries']);
    }

    public function create()
    {
        $zones = $this->timeZoneService->zones();
        $currencies = Currency::all();
        $timezone = null;
        if(old('time_zone_id')) {
            $timezone = TimeZone::find(old('time_zone_id'));
        }
        return view('region::country.create', compact('zones', 'currencies', 'timezone'))->with(['title' => 'Add new country']);
    }

    public function store(CountryCreateRequest $request): RedirectResponse
    {
        return $this->countryService->create($request);
    }

    public function show(Country $country)
    {
        return view('region::country.show', compact('country'))->with(['title' => $country->name]);
    }

    public function edit(Country $country)
    {
        $zones = $this->timeZoneService->zones();
        $currencies = Currency::all();
        $timezone = null;
        if(old('time_zone_id', $country->time_zone_id)) {
            $timezone = TimeZone::find(old('time_zone_id', $country->time_zone_id));
        }
        return view('region::country.edit', compact('country', 'currencies', 'zones', 'timezone'))->with(['title' => 'Update country']);
    }

    public function update(CountryUpdateRequest $request, $id): RedirectResponse
    {
        return $this->countryService->update($request, $id);
    }

    public function destroy($id)
    {
        //
    }

    public function suggestion(Request $request): JsonResponse
    {
        return $this->countryService->suggestion($request);
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['suggestions', 'delete'])) {
            $this->authorize($method, Country::class);
        }
        return parent::callAction($method, $parameters);
    }
}
