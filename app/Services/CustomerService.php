<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class CustomerService
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function all(Request $request)
    {
        $customers = Customer::filter($request)
            ->latest()
            ->paginate($request->input('per_page', 10));
        return response()->success(CommonHelper::parsePaginator($customers));
    }

    public function create($data)
    {
        try {
            $this->customerRepository->create($data);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_CREATE_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function update($data, Customer $customer)
    {
        try {
            $this->customerRepository->update(array_filter($data), $customer->id);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_UPDATE_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function delete(User $user)
    {
        try {
            $user->delete();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_DESTROY_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }
}
