<?php

namespace App\Services;

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
        return response()->success(
            Customer::filter($request)
                ->paginate($request->per_page ?? 10)
        );
    }

    public function create($data)
    {
        try {
            $user = $this->customerRepository->create($data);
            $user->assignRole(Role::find($data['role_id']));
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_CREATE_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function update($data, $id)
    {
        try {
            $user = $this->customerRepository->update($data, $id);
            $user->syncRoles(Role::find($data['role_id']));
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
