<?php

namespace Modules\CMS\App\Services;

use Modules\Bundle\App\Constant\BundleConstant;
use Modules\Bundle\Entities\Bundle;
use Modules\Bundle\Repositories\BundleRepository;
use Modules\Bundle\Services\BundleService;
use Modules\Operator\App\Constant\OperatorConstant;
use Modules\Operator\Entities\Operator;
use Modules\Operator\Repositories\OperatorRepository;
use Modules\Operator\Services\OperatorService;
use Modules\Order\App\Services\OrderService;
use Modules\ServiceType\Services\ServiceTypes;

class CmsService
{
    public function search($request): array
    {
        return [
            'max_price' => app(BundleService::class)->getMaxPrice($request),
            'operators' => app(OperatorService::class)->search($request),
            'products' => app(BundleService::class)->search($request)
        ];
    }

    public function recommended($request)
    {
        $query = app(BundleRepository::class)->getModel();

        $mostPurchaseIds = app(OrderService::class)->mostPurchasedItems($request->input('customer_id', auth('api')->id()));
        if ($mostPurchaseIds) {
            $query->whereIn('id', $mostPurchaseIds);
        }

        return $query->where('status', BundleConstant::ACTIVE)
            ->whereHas('operator', function ($query) {
                $query->where('status', OperatorConstant::ACTIVE);
            })
            ->filter($request, true)
            ->limit(getOption('recommendation_item_limit', 3))
            ->get()
            ->map(function (Bundle $bundle) {
                return $bundle->format();
            });
    }

    public function getGiftCards($request): array
    {
        $query = app(BundleRepository::class)->getModel()
            ->whereIn('operator_id', app(OperatorService::class)->servicesOperators(getOption('section4_service_type_id', 1)))
            ->where('status', BundleConstant::ACTIVE);
        $count = $query->count();
        $maxPrice = $query->max('selling_price');
        $products = $query->filter($request, true)
            ->orderBy('position')
            ->take($request->input('limit', 6))
            ->get();
        return [
            'total' => $count,
            'max_price' => $maxPrice,
            'limit' => $request->input('limit', 6),
            'categories' => app(ServiceTypes::class)->getCategories(getOption('section4_service_type_id', 1)),
            'results' => $products->map(function (Bundle $bundle) {
                return $bundle->format();
            })
        ];
    }

    public function mobileRecharge($request): array
    {
        $query = app(OperatorRepository::class)->getModel()
            ->where('category_id', getOption('mobile_recharge_category_id', 1))
            ->where('status', OperatorConstant::ACTIVE);
        $count = $query->count();
        $products = $query->filter($request, true)
            ->orderBy('position')
            ->take($request->input('limit', 6))
            ->get();
        return [
            'total' => $count,
            'results' => $products->map(function (Operator $operator) {
                return $operator->format();
            })
        ];
    }

    public function internetRecharge($request): array
    {
        $query = app(OperatorRepository::class)->getModel()
            ->where('category_id', getOption('internet_recharge_category_id', 1))
            ->where('status', OperatorConstant::ACTIVE);
        $count = $query->count();
        $products = $query->filter($request, true)
            ->orderBy('position')
            ->take($request->input('limit', 6))
            ->get();
        return [
            'total' => $count,
            'results' => $products->map(function (Operator $operator) {
                return $operator->format();
            })
        ];
    }
}
