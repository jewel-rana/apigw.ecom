<?php

namespace Modules\Provider\Services;

use App\Constants\LogConstant;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Bundle\Entities\Bundle;
use Modules\Provider\Entities\Provider;
use Modules\Provider\Http\Requests\ProductTagRequest;
use Modules\Provider\Repositories\Interfaces\ProviderRepositoryInterface;

class ProviderService
{
    private ProviderRepositoryInterface $providerRepository;

    public function __construct(ProviderRepositoryInterface $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }

    public function all()
    {
        return Cache::remember('providers', 3600, function () {
            return $this->providerRepository->all();
        });
    }

    public function get($id)
    {
        return $this->providerRepository->show($id);
    }

    public function create(array $data)
    {
        return $this->providerRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->providerRepository->update($data, $id);
    }

    public function getDataTable($request): JsonResponse
    {
        return datatables()->eloquent(
            $this->providerRepository->getModel()->filter($request)
        )
            ->addColumn('status', function (Provider $provider) {
                return $provider->nice_status;
            })
            ->addColumn('last_deposit', function (Provider $provider) {
                return $provider->deposit->amount_iqd ?? 0;
            })
            ->addColumn('actions', function (Provider $provider) {
                $str = '';
                $str .= "<a href='" . route('provider.show', $provider->id ). "' class='btn btn-default'><i class='fa fa-eye'></i></a>";
                $str .= "<a href='" . route('provider.edit', $provider->id) . "' class='btn btn-primary'><i class='fa fa-edit'></i></a>";
                return $str;
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function getSuggestions(Request $request): JsonResponse
    {
        try {
            $data = $this->providerRepository->all()
                ->filter(function ($provider) use ($request) {
                    $matched = true;
                    if($request->has('term')) {
                        $matched = CommonHelper::matchText($provider->name, $request->input('term'));
                    }
                    return $matched;
                })
                ->map(function ($provider, $key) {
                    return [
                        'id' => $provider->id,
                        'text' => $provider->name
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'No data!']);
        }
    }

    public function attachProduct(Bundle $bundle): bool
    {
        try {
            $provider = $this->get(1);
            $provider->operators()->syncWithoutDetaching([$bundle->operator_id => ['user_id' => auth()->id()]]);
            $provider->bundles()->syncWithoutDetaching([$bundle->id => ['user_id' => auth()->id()]]);
            return true;
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PROVIDER_ATTACH_PRODUCT_EXCEPTION'
            ]);
            return false;
        }
    }
}
