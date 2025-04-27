<?php

namespace Modules\Order\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\App\Models\Refund;
use Modules\Order\App\Services\RefundService;

class OrderRefundController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    private RefundService $refundService;

    public function __construct(RefundService $refundService)
    {
        $this->refundService = $refundService;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->refundService->getDataTable($request);
        }

        return view('order::refund.index');
    }

    public function store(Request $request): RedirectResponse
    {
        //
    }

    public function show(Refund $refund)
    {
        return view('order::refund.show', compact('refund'));
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['suggestions'])) {
            $this->authorize($method, Refund::class);
        }
        return parent::callAction($method, $parameters);
    }

    public function export(Request $request)
    {
        return $this->refundService->exportRefundData($request);
    }
}
