<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Operator\Entities\Operator;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchaseItem;
use Modules\Purchase\Http\Requests\PurchaseItemUpdateRequest;
use Modules\Purchase\Services\PurchaseItemService;

class PurchaseItemController extends Controller
{
    private PurchaseItemService $itemService;

    public function __construct(PurchaseItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->itemService->getDataTables($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseItem $item)
    {
        return view('purchase::items.edit',[
            'purchaseItem' => $item,
            'operators' => Operator::whereStatus(1)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseItemUpdateRequest $request, PurchaseItem $item): RedirectResponse
    {
        return $this->itemService->update($request, $item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseItem $item)
    {
        return $this->itemService->destroy($item);
    }

    public function suggestions(Request $request): JsonResponse
    {
        return $this->itemService->getSuggestions($request);
    }
}
