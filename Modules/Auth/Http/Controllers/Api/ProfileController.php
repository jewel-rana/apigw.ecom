<?php

namespace Modules\Auth\Http\Controllers\Api;

use App\Helpers\LogHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Auth\App\Http\Requests\Api\UpdateProfile;
use Modules\Customer\App\Services\CustomerService;

class ProfileController extends Controller
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        return response()->success(
            auth()->user()->format()
        );
    }

    public function update(UpdateProfile $request)
    {
        return $this->customerService->update($request->validated(), auth()->id());
    }
}
