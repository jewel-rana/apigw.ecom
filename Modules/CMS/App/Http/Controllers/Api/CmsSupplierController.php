<?php

namespace Modules\CMS\App\Http\Controllers\Api;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Provider\Entities\Provider;
use Modules\Provider\Services\ProviderService;

class CmsSupplierController extends Controller
{
    private ProviderService $providerService;

    public function __construct(ProviderService $providerService)
    {
        $this->providerService = $providerService;
    }

    public function index(Request $request)
    {
        return response()->success(
            $this->providerService->all()->map(function ($item) {
                return $item->format();
            })
        );
    }

    public function show(Provider $supplier, Request $request)
    {
        return response()->success(
            $supplier->format() + [
                'products' => CommonHelper::parsePaginator(
                    $supplier->products()->paginate($request->input('perPage', 10))
                )
            ]
        );
    }
}
