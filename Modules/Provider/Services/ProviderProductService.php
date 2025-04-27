<?php

namespace Modules\Provider\Services;

use Illuminate\Http\Request;
use Modules\Bundle\Entities\Bundle;
use Modules\Provider\Entities\Provider;
use Modules\Provider\Http\Requests\ProductTagRequest;
use Yajra\DataTables\DataTables;

class ProviderProductService
{
    public function getDatatable(Request $request)
    {
        $provider = Provider::find($request->input('provider_id'));
        $bundles = $provider->bundles()->paginate(15);
        return Datatables::of($bundles->items())
            ->with([
                "recordsTotal" => $bundles->total(),
                "recordsFiltered" => $bundles->total(),
            ])
            ->addColumn('operator', function ($item) {
                return $item->operator->name ?? '---';
            })
            ->addColumn('actions', function ($item) use ($provider){
                return '<button class="btn btn-danger btn-sm deleteBtn" href="' . route('provider.product.destroy', [$item->id, 'provider_id' => $provider->id]) . '" data-type="product" title="Detach product"><i class="fa fa-times"></i></button>';
            })
            ->rawColumns(['actions'])
            ->toJson();

    }

    public function create(ProductTagRequest $request)
    {
        try {
            $provider = Provider::find($request->input('provider_id'));
            $bundle = Bundle::find($request->input('bundle_id'));
//            $bundle->providers()->sync([]);
            $provider->operators()->syncWithoutDetaching([
                $request->input('operator_id') => ['user_id' => auth()->id()]
            ]);
            $provider->bundles()->syncWithoutDetaching([
                $request->input('bundle_id') => ['user_id' => auth()->id()]
            ]);
            return response()->success();
        } catch (\Exception $exception) {
            return response()->error();
        }
    }

    public function delete(Request $request)
    {
        try {
            $provider = Provider::find($request->input('provider_id'));
            $provider->bundles()->detach($request->input('bundle_id'));
            if(!$provider->bundles()->where('operator_id', $request->input('operator_id'))->count()) {
                $provider->operators()->detach($request->input('operator_id'));
            }
            return response()->success();
        } catch (\Exception $exception) {
            return response()->error();
        }
    }
}
