<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Http\Requests\OrderCreateRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        return $this->orderService->form($request);
    }

    public function store(OrderCreateRequest $request)
    {
        return $this->orderService->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
