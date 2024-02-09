<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Customer;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerCreateRequest;

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

    public function export(Request $request)
    {
//        return (new OrderExport($request))->raw(Excel::XLSX);
        return (new OrderExport($request))->download('order.xlsx');
    }
}
