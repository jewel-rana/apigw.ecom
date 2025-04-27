<?php

namespace Modules\Region\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Region\Entities\Country;
use Modules\Region\Http\Requests\CountryCreateRequest;
use Modules\Region\Repositories\Interfaces\CountryRepositoryInterface;

class CountryService
{
    private CountryRepositoryInterface $countryRepository;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function all()
    {
        return Cache::rememberForever('countries', function () {
            return $this->countryRepository->all();
        });
    }

    public function get(int $id)
    {
        return $this->countryRepository->show($id);
    }

    public function getDataTables(Request $request): JsonResponse
    {
        return datatables()->eloquent(
            $this->countryRepository->with(['currency', 'timezone'])
        )
            ->addColumn('actions', function (Country $country) {
                if(!$country->is_default)
                    return '<a href="' . route('country.edit', $country->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function create(CountryCreateRequest $request): RedirectResponse
    {
        try {
            $this->countryRepository->create($request->validated());
            return redirect()->route('country.index')->with(['status' => true, 'message' => __('Country created successfully')]);
        } catch (\Exception $exception) {
            dd($exception);
            LogHelper::exception($exception, [
                'keyword' => 'COUNTRY_CREATE_EXCEPTION'
            ]);
            return redirect()->back()->withInput($request->all())->with(['status' => false, 'message' => __('Failed to create country')]);
        }
    }

    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $this->countryRepository->update($request->validated(), $id);
            return redirect()->route('country.index')->with(['status' => true, 'message' => __('Country updated successfully')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'COUNTRY_UPDATE_EXCEPTION'
            ]);
            return redirect()->back()->withInput($request->all())->with(['status' => false, 'message' => __('Failed to update country')]);
        }
    }

    public function suggestion(Request $request): JsonResponse
    {
        try {
            $data = $this->countryRepository->all()
                ->filter(function ($country) use ($request) {
                    $matched = true;
                    if($request->has('term')) {
                        $matched = CommonHelper::matchText($country->name, $request->input('term'));
                    }
                    return $matched;
                })
                ->map(function ($country, $key) {
                    return [
                        'id' => $country->id,
                        'text' => $country->name . ' (' . $country->code . ')'
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'No data!']);
        }
    }

    public function getCountries(Request $request)
    {
        return $this->all()->filter(function($item) use ($request) {
            if($request->has('term')) {
                return CommonHelper::matchText($item->name, $request->input('term'));
            }
            return true;
        })
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name
                ];
            });
    }
}
