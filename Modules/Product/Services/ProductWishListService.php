<?php

namespace Modules\Product\Services;

use App\Helpers\CommonHelper;
use Modules\Product\Entities\ProductWishList;

class ProductWishListService
{
    public function index(\Illuminate\Http\Request $request)
    {
        $wishLists = ProductWishList::where('customer_id', auth('customer')->id())
            ->with(['product' => function ($query) {
                $query->select('id', 'name', 'slug', 'image', 'price');
            }])
            ->filter($request)
            ->paginate($request->get('per_page', 10));

        return response()->success(
            CommonHelper::parsePaginator($wishLists)
        );
    }
}
