<?php

namespace Modules\Region\Services;

use App\Helpers\CommonHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Region\Repositories\Interfaces\TimeZoneRepositoryInterface;

class TimeZoneService
{
    private TimeZoneRepositoryInterface $timeZoneRepository;

    public function __construct(TimeZoneRepositoryInterface $timeZoneRepository)
    {
        $this->timeZoneRepository = $timeZoneRepository;
    }

    public function all()
    {
        return Cache::rememberForever('timezones', function () {
            return $this->timeZoneRepository->all();
        });
    }

    public function zones()
    {
        return $this->all()->where('parent', 0);
    }

    public function suggestion(Request $request): JsonResponse
    {
        try {
            $data = $this->timeZoneRepository->all()
                ->filter(function ($timezone) use ($request) {
                    $matched = true;
                    if($request->has('country_id')) {
                        $country = app(CountryService::class)->get($request->input('country_id'));
                        if($country) {
                            $matched = $country->zone_id == $timezone->parent;
                        }
                    }
                    if($request->has('term')) {
                        $matched = CommonHelper::matchText($timezone->name, $request->input('term'));
                    }
                    return $matched;
                })
                ->map(function ($timezone, $key) {
                    return [
                        'id' => $timezone->id,
                        'text' => $timezone->name
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'No data!']);
        }
    }
}
