<?php

namespace Modules\Provider\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Provider\Entities\Provider;
use Modules\Provider\Http\Requests\ProviderCashDepositRequest;
use Modules\Provider\Services\ProviderCashService;
use Modules\Vendor\Entities\Vendor;

class ProviderCashController extends Controller
{
    private ProviderCashService $cashService;

    public function __construct(ProviderCashService $cashService)
    {
        $this->cashService = $cashService;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->cashService->getDataTables($request);
        }
        return view('provider::cash.index')->with(['title' => 'Deposits']);
    }

    public function create(Request $request)
    {
        $provider = null;
        if(old('provider_id', $request->input('provider_id'))) {
            $provider = Provider::find(old('provider_id', $request->input('provider_id')));
        }
        return view('provider::cash.create', compact('provider'));
    }

    public function store(ProviderCashDepositRequest $request): RedirectResponse
    {
        return $this->cashService->create($request);
    }
}
