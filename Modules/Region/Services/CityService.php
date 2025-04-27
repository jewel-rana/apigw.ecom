<?php

namespace Modules\Region\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Region\App\Models\City;
use Modules\Region\Http\Requests\StoreCityRequest;
use Modules\Region\Http\Requests\UpdateCityRequest;
use Modules\Region\Repositories\Interfaces\CityRepositoryInterface;

class CityService
{
    private CityRepositoryInterface $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function all()
    {
        return Cache::rememberForever('cities', function () {
            return $this->cityRepository->all();
        });
    }

    public function getDataTables(Request $request): JsonResponse
    {
        return datatables()->eloquent(
            $this->cityRepository->with(['country', 'timezone'])
        )
            ->addColumn('status', function (City $region) {
                return $region->nice_status;
            })
            ->addColumn('actions', function (City $region) {
                if(!$region->is_default)
                    return '<a href="' . route('city.edit', $region->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function create(StoreCityRequest $request): RedirectResponse
    {
        try {
            $this->cityRepository->create($request->validated());
            return redirect()->route('city.index')->with(['status' => true, 'message' => __('City created successfully')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'REGION_CREATE_EXCEPTION'
            ]);
            return redirect()->back()->withInput($request->all())->with(['status' => false, 'message' => __('Failed to create city')]);
        }
    }

    public function update(UpdateCityRequest $request, $id): RedirectResponse
    {
        try {
            $this->cityRepository->update($request->validated(), $id);
            return redirect()->route('city.index')->with(['status' => true, 'message' => __('Region updated successfully')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'REGION_UPDATE_EXCEPTION'
            ]);
            return redirect()->back()->withInput($request->all())->with(['status' => false, 'message' => __('Failed to update region')]);
        }
    }

    public function suggestion(Request $request): JsonResponse
    {
        try {
            $data = $this->cityRepository->all()
                ->where('country_id', $request->input('country_id'))
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
                        'text' => $country->name
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'No data!']);
        }
    }

    public function getCities(Request $request)
    {
        return $this->all()->filter(function($item) use ($request) {
            $match = true;
            if($request->has('country_id')) {
                $match = $item->country_id == $request->input('country_id');
            }
            if($request->has('term')) {
                $match = CommonHelper::matchText($item->name, $request->input('term'));
            }
            return $match;
        })
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name
                ];
            });
    }
}
