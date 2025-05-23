<?php

namespace Modules\Provider\Http\Controllers;

use App\Constants\LogConstant;
use App\Helpers\LogHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
        if ($request->ajax() || $request->wantsJson()) {
            return $this->providerService->getDataTable($request);
        }
        return view('provider::index');
    }

    public function create()
    {
        return view('provider::create');
    }

    public function store(ProviderCreateRequest $request): RedirectResponse
    {
        try {
            $this->providerService->create($request->validated());
            return redirect()->route('provider.index')->with(['message' => 'Provider successfully created']);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => LogConstant::EXCEPTION_GENERAL
            ]);
            return redirect()->back()->withInput($data)->withErrors(['message' => $exception->getMessage()]);
        }
    }

    public function show(Provider $provider)
    {
        return view('provider::show', compact('provider'));
    }

    public function edit(Provider $provider)
    {
        return view('provider::edit', compact('provider'));
    }

    public function update(ProviderUpdateRequest $request, $id): RedirectResponse
    {
        try {
            $this->providerService->update($request->validated(), $id);
            return redirect()->route('provider.index')->with(['message' => 'Provider successfully updated']);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => LogConstant::EXCEPTION_GENERAL
            ]);
            return redirect()->back()->withInput($data)->withErrors(['message' => $exception->getMessage()]);
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
