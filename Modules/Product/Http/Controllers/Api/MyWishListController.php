<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\ProductWishList;
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

    public function store(Request $request)
    {
        try {
            ProductWishList::updateOrCreate(
                [
                    'customer_id' => auth('customer')->id(),
                    'product_id' => $request->product_id,
                ],
                [
                    'customer_id' => auth('customer')->id(),
                    'product_id' => $request->product_id,
                ]
            );

            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PRODUCT_WISH_LIST_EXCEPTION',
            ]);

            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function destroy(ProductWishList $wishlist)
    {
        try {
            $wishlist->delete();
            return response()->success();
        } catch (\Exception $exception) {
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }
}
