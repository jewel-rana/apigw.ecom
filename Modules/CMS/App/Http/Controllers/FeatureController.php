<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMS\App\Models\Feature;
use Modules\CMS\Http\Requests\StoreFeatureRequest;
use Modules\CMS\Http\Requests\UpdateFeatureRequest;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        $features = Feature::filter($request)->paginate($request->input('per_page', 10));
        return response()->success(CommonHelper::parsePaginator($features));
    }

    public function store(StoreFeatureRequest $request)
    {
        try {
            Feature::create($request->validated());
            return response()->success();
        } catch (\Exception $e) {
            return response()->failed(['message' => $e->getMessage()]);
        }
    }

    public function show(Feature $feature)
    {
        return response()->success($feature->format());
    }

    public function update(UpdateFeatureRequest $request, Feature $feature)
    {
        try {
            $feature->update($request->validated());
            return response()->success();
        } catch (\Exception $e) {
            return response()->failed(['message' => $e->getMessage()]);
        }
    }

    public function destroy(Feature $feature)
    {
        try {
            $feature->delete();
            return response()->success();
        } catch (\Exception $e) {
            return response()->failed(['message' => $e->getMessage()]);
        }
    }
}
