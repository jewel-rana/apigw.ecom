<?php

namespace Modules\CMS\App\Http\Controllers\Api;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMS\App\Models\Feature;

class FeatureProductController extends Controller
{
    public function index(Request $request, Feature $feature)
    {
        $data = $feature->format();
        $data['products'] = CommonHelper::parsePaginator(
            $feature->products()->filter($request)->paginate($request->input('per_page', 10))
        );

        return response()->success($data);
    }

    public function store(Feature $feature, Request $request)
    {
        try {
            $feature->products()->syncWithPivotValues(
                $request->input('product_id'),
                [
                    'position' => $request->input('position', 0)
                ]
            );
            return response()->success();
        } catch (\Exception $exception) {
            return response()->error($exception->getMessage());
        }
    }

    public function destroy(Feature $feature, $productId)
    {
        try {
            $feature->products()->detach([$productId]);
            return response()->success();
        } catch (\Exception $exception) {
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }
}
