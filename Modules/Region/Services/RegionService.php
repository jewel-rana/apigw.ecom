<?php

namespace Modules\Region\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Region\Entities\Region;
use Modules\Region\Http\Requests\RegionCreateRequest;
use Modules\Region\Http\Requests\RegionUpdateRequest;
use Modules\Region\Repositories\Interfaces\RegionRepositoryInterface;

class RegionService
{
    private RegionRepositoryInterface $regionRepository;

    public function __construct(RegionRepositoryInterface $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function all()
    {
        return Cache::rememberForever('regions', function () {
            return $this->regionRepository->all();
        });
    }

    public function getDataTables(Request $request): JsonResponse
    {
        return datatables()->eloquent(
            $this->regionRepository->getModel()->query()
        )
            ->addColumn('status', function (Region $region) {
                return $region->nice_status;
            })
            ->addColumn('flag', function (Region $region) {
                return "<img src='{$region->flag}' />";
            })
            ->addColumn('actions', function (Region $region) {
                if (!$region->is_default)
                    return '<a href="' . route('region.edit', $region->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>';
            })
            ->rawColumns(['actions', 'flag'])
            ->toJson();
    }

    public function create(RegionCreateRequest $request): RedirectResponse
    {
        try {
            $this->regionRepository->create($request->validated());
            return redirect()->route('region.index')->with(['status' => true, 'message' => __('Region created successfully')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'REGION_CREATE_EXCEPTION'
            ]);
            return redirect()->back()->withInput($request->all())->with(['status' => false, 'message' => __('Failed to create region')]);
        }
    }

    public function update(RegionUpdateRequest $request, $id): RedirectResponse
    {
        try {
            $this->regionRepository->update($request->validated(), $id);
            return redirect()->route('region.index')->with(['status' => true, 'message' => __('Region updated successfully')]);
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
            $data = $this->regionRepository->all()
                ->where('country_id', $request->input('country_id'))
                ->filter(function ($country) use ($request) {
                    $matched = true;
                    if ($request->has('term')) {
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
        return app(CityService::class)->all()
            ->filter(function ($item) use ($request) {
                $match = true;
                if ($request->filled('country_id')) {
                    $match = $item->country_id == $request->input('country_id');
                }
                if ($request->filled('term')) {
                    $match = CommonHelper::matchText($item->name, $request->input('term'));
                }
                return $match;
            })
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name
                ];
            })->values();
    }


    public function getRegions(Request $request)
    {
        return $this->all()->filter(function ($item) use ($request) {
            $match = true;
            if ($request->has('term')) {
                $match = CommonHelper::matchText($item->name, $request->input('term'));
            }
            return $match;
        })
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name
                ];
            });
    }
}
