<?php

namespace Modules\Product\Services;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Entities\ProductWishList;

class ProductWishListService
{
    public function index(Request $request)
    {
        $wishLists = ProductWishList::where('customer_id', auth('customer')->id())
            ->with(['product'])
            ->filter($request)
            ->paginate($request->get('per_page', 10));

        return response()->success(
            CommonHelper::parsePaginator($wishLists)
        );
    }

    public function getProductIds()
    {
        return Cache::remember('wishlists', 1200, function (){
            return ProductWishList::pluck('product_id')->toArray();
        });
    }
}
