<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerCreateRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        return $this->customerService->all($request);
    }

    public function store(CustomerCreateRequest $request)
    {
        return $this->customerService->create($request->validated());
    }

    public function show(Customer $customer)
    {
        return response()->success($customer->format());
    }

    public function update(CustomerUpdateRequest $request, string $id)
    {
        return $this->customerService->update($request->validated(), $id);
    }

    public function destroy(User $user)
    {
        return $this->customerService->delete($user);
    }

    public function export(Request $request): BinaryFileResponse
    {
        return (new CustomerExport($request))->download('customers.xlsx');
    }

    public function callAction($method, $parameters): Response
    {
        if(Arr::hasAny(['export'], $method)) {
            $this->authorize($method, Customer::class);
        }

        return parent::callAction($method, $parameters);
    }
}
