<?php

namespace Modules\Shipping\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Shipping\Http\Requests\UpdateShippingRequest;
use Modules\Shipping\Repositories\Interfaces\ShippingRepositoryInterface;

class ShippingService
{
    private ShippingRepositoryInterface $shippingRepository;

    public function __construct(ShippingRepositoryInterface $shippingRepository)
    {
        $this->shippingRepository = $shippingRepository;
    }

    public function all($request)
    {
        return Cache::rememberForever('shippings', function () use ($request) {
            return $this->shippingRepository->getModel()
                ->filter($request)
                ->latest()
                ->get();
        });
    }

    public function index(Request $request)
    {
        $agents = $this->shippingRepository->getModel()
            ->filter($request)
            ->latest()
            ->paginate(CommonHelper::perPage($request));
        return response()->success(CommonHelper::parsePaginator($agents));
    }

    public function create(Request $request)
    {
        try {
            $this->shippingRepository->create($request->validated());
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PAYMENT_CREATE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function update(UpdateShippingRequest $request, $id)
    {
        try {
            $this->shippingRepository->update($request->validated(), $id);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PAYMENT_CREATE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function suggestions(Request $request)
    {
        try {
            return response()->success(
                $this->all($request)
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                        ];
                    })
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'AGENT_NOT_FOUND_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

}
