<?php

namespace Modules\Gateway\Http\Controllers\Api;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Gateway\Entities\Gateway;
use Modules\Gateway\GatewayService;

class GatewayController extends Controller
{
    private GatewayService $gatewayService;

    public function __construct(GatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    public function index(Request $request)
    {
        $menus = Gateway::filter($request)
            ->latest()
            ->paginate(CommonHelper::perPage($request));

        return response()->success(CommonHelper::parsePaginator($menus));
    }

    public function store(Request $request)
    {
        return response()->success();
    }

    public function show(Gateway $gateway)
    {
        return response()->success(
            $gateway->format()
        );
    }

    public function update(Request $request, $id)
    {
        return response()->success();
    }

    public function destroy($id)
    {
        return response()->success();
    }

    public function suggestion(Request $request)
    {
        return $this->gatewayService->suggestions($request);
    }
}
