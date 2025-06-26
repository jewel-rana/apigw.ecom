<?php

namespace Modules\CMS\App\Services;

use App\Helpers\LogHelper;
use Modules\CMS\App\Models\Feature;
use Modules\Order\App\Services\OrderService;
use Modules\Product\Constants\ProductConstant;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Services\ProductService;

class CmsService
{
    public function search($request): array
    {
        return app(Product::class)->search($request);
    }

    public function recommended($request)
    {
        $query = app(ProductRepository::class)->getModel();

        $mostPurchaseIds = app(OrderService::class)->mostPurchasedItems($request->input('customer_id', auth('api')->id()));
        if ($mostPurchaseIds) {
            $query->whereIn('id', $mostPurchaseIds);
        }

        return $query->where('status', ProductConstant::ACTIVE)
            ->filter($request, true)
            ->limit(getOption('recommendation_item_limit', 3))
            ->get()
            ->map(function (Product $product) {
                return $product->format();
            });
    }

    public function featured($request): array
    {
        $response = [];
        try {
            $features = Feature::active()->all();
            foreach ($features as $feature) {
                $products = app(ProductService::class)->featureProducts($request, $feature);
                $response[] = [
                    'key' => $feature->title,
                    'description' => $feature->description,
                    'products' => $products
                ];
            }
        } catch (\Throwable $th) {
            LogHelper::error('feature.products', [
                'message' => $th->getMessage(),
                'keyword' => 'FEATURED_PRODUCT_EXCEPTION'
            ]);
        }
        return $response;
    }
}
