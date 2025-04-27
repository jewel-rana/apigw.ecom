<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Operator\Entities\Operator;
use Modules\Provider\Entities\Provider;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Enums\PurchaseStatusEnum;
use Modules\Purchase\Http\Requests\PurchaseCreateRequest;
use Modules\Purchase\Http\Requests\PurchaseUpdateRequest;
use Modules\Purchase\Services\PurchaseService;

class PurchaseController extends Controller
{
    use ValidatesRequests, AuthorizesRequests;
    private PurchaseService $purchaseService;
    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index(Request $request)
    {
        if($request->acceptsJson() && $request->wantsJson()) {
            return $this->purchaseService->getDataTables($request);
        }
        return view('purchase::index');
    }

    public function create()
    {
        return view('purchase::create', [
            'statuses' => PurchaseStatusEnum::statuses(),
            'suppliers' => Provider::whereStatus(1)->get(),
            'operators' => Operator::whereStatus(1)->get(),
            'currencies' => config('currency.list'),
        ])->with(['title' => 'Add new purchase']);
    }

    public function store(PurchaseCreateRequest $request): RedirectResponse
    {
        return $this->purchaseService->store($request);
    }

    public function show(Purchase $purchase)
    {
        return view('purchase::show', compact('purchase'))->with(['title' => 'View Purchase Order']);
    }

    public function edit(Purchase $purchase)
    {
        return view('purchase::edit',[
            'statuses' => PurchaseStatusEnum::statuses(),
            'operators' => Operator::whereStatus(1)->get(),
            'suppliers' => Provider::whereStatus(1)->get(),
            'currencies' => config('currency.list'),
            'purchase' => $purchase,
        ]);
    }

    public function update(PurchaseUpdateRequest $request, Purchase $purchase)
    {
        return $this->purchaseService->update($request, $purchase);
    }

    public function destroy($id)
    {
        //
    }

    public function suggestions(Request $request): JsonResponse
    {
        return $this->purchaseService->getSuggestions($request);
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['suggestions'])) {
            $this->authorize($method, Purchase::class);
        }
        return parent::callAction($method, $parameters);
    }
}
