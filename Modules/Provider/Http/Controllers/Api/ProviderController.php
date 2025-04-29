<?php

namespace Modules\Provider\Http\Controllers\Api;

use App\Constants\LogConstant;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Provider\Entities\Provider;
use Modules\Provider\Http\Requests\ProviderCreateRequest;
use Modules\Provider\Http\Requests\ProviderUpdateRequest;
use Modules\Provider\Services\ProviderService;

class ProviderController extends Controller
{
    use ValidatesRequests, AuthorizesRequests;

    private ProviderService $providerService;

    public function __construct(ProviderService $providerService)
    {
        $this->providerService = $providerService;
    }

    public function index(Request $request)
    {
        $providers = Provider::with(['createdBy', 'updatedBy'])
            ->filter($request)
            ->orderBy('created_at', $request->order ?? 'DESC')
            ->paginate($request->input('per_page', 10));
        return response()->success(CommonHelper::parsePaginator($providers));
    }

    public function store(ProviderCreateRequest $request)
    {
        try {
            $this->providerService->create($request->validated());
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => LogConstant::EXCEPTION_GENERAL
            ]);

            return response()->error($exception->getMessage());
        }
    }

    public function show(Provider $provider)
    {
        return response()->success($provider->format());
    }

    public function update(ProviderUpdateRequest $request, $id)
    {
        try {
            $this->providerService->update($request->validated(), $id);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => LogConstant::EXCEPTION_GENERAL
            ]);
            return response()->error();
        }
    }

    public function destroy($id)
    {
        //
    }

    public function suggestion(Request $request): JsonResponse
    {
        return $this->providerService->getSuggestions($request);
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['suggestion'])) {
            $this->authorize($method, Provider::class);
        }
        return parent::callAction($method, $parameters);
    }
}
