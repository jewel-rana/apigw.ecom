<?php

namespace Modules\Shipping\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shipping\Http\Requests\StoreShippingRequest;
use Modules\Shipping\Http\Requests\UpdateShippingRequest;
use Modules\Shipping\Services\ShippingService;

class ShippingController extends Controller
{
    private ShippingService $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    public function index(Request $request)
    {
        return $this->shippingService->index($request);
    }

    public function store(StoreShippingRequest $request)
    {
        return $this->shippingService->create($request);
    }

    public function update(UpdateShippingRequest $request, $id)
    {
        return $this->shippingService->update($request, $id);
    }
}
