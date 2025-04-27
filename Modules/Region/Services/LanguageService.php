<?php

namespace Modules\Region\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Region\Entities\Language;
use Modules\Region\Repositories\Interfaces\LanguageRepositoryInterface;

class LanguageService
{
    private LanguageRepositoryInterface $languageRepository;

    public function __construct(LanguageRepositoryInterface $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function all()
    {
        return Cache::rememberForever('languages', function () {
            return $this->languageRepository->all();
        });
    }

    public function getDataTables(Request $request): JsonResponse
    {
        return datatables()->eloquent(
            $this->languageRepository->getModel()->query()
        )

            ->addColumn('flag', function ($item) {
                return "<img src='" . asset('default/languages/' . $item->code . '.png') . "' />";
            })
            ->addColumn('status', function (Language $language) {
                return $language->nice_status;
            })
            ->addColumn('actions', function (Language $language) {
                if(!$language->is_default)
                    return '<a href="' . route('language.edit', $language->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>';
            })
            ->rawColumns(['actions', 'flag'])
            ->toJson();
    }

    public function cms()
    {
        return $this->all()->map(function ($language) {
            return $language->format();
        });
    }
}
