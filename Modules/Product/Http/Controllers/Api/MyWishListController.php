<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductWishList;
use Modules\Product\Http\Requests\StoreProductWishlistRequest;
use Modules\Product\Services\ProductWishListService;

class MyWishListController extends Controller
{
    private ProductWishListService $wishListService;

    public function __construct(ProductWishListService $wishListService)
    {
        $this->middleware('auth:customer');
        $this->wishListService = $wishListService;
    }

    public function index(Request $request)
    {
        return $this->wishListService->index($request);
    }

    public function store(StoreProductWishlistRequest $request)
    {
        try {
            $wishlist = ProductWishList::updateOrCreate(
                [
                    'customer_id' => auth('customer')->id(),
                    'product_id' => $request->product_id,
                ],
                [
                    'customer_id' => auth('customer')->id(),
                    'product_id' => $request->product_id,
                ]
            );

            Cache::forget('wishlists');

            return response()->success($wishlist->only(['id', 'product_id']));
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PRODUCT_WISH_LIST_EXCEPTION',
            ]);

            return response()->failed(['message' => $exception->getMessage()]);
        }
    }


    public function destroy(Product $product)
    {
        try {
            $product->wishLists()
                ->where('customer_id', auth('customer')->id())
                ->delete();
            Cache::forget('wishlists');
            return response()->success();
        } catch (\Exception $exception) {
            return response()->failed(['message' => 'Internal Server Error!']);
        }
    }
}
