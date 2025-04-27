<?php

namespace Modules\Customer\App\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\App\Http\Requests\CustomerActionRequest;
use Modules\Customer\App\Models\Customer;
use Modules\Customer\App\Services\CustomerService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->customerService->getDataTable($request);
        }
        return view('customer::index');
    }

    public function show(Customer $customer)
    {
        return view('customer::show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customer::edit');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    public function action(CustomerActionRequest $request, $id): JsonResponse
    {
        try {
            $this->customerService->update(['status' => $request->input('action')], $id);
            return response()->json(['status' => true, 'message' => __('Success')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CUSTOMER_ACTION_EXCEPTION'
            ]);
            return response()->json(['status' => false, 'message' => __('Failed')]);
        }
    }

    public function destroy(Customer $customer)
    {
        //
    }

    public function suggestions(Request $request)
    {
        return $this->customerService->suggestions($request);
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['suggestions', 'action'])) {
            $this->authorize($method, Customer::class);
        }
        return parent::callAction($method, $parameters);
    }

    public function export(Request $request): StreamedResponse
    {
        return $this->customerService->exportCustomerData($request);
    }

    public function orderExport(Request $request, Customer $customer): StreamedResponse
    {
        return $this->customerService->exportCustomerOrderData($request, $customer);
    }
}
