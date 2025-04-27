<?php

namespace Modules\Auth\App\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Auth\App\Http\Requests\SocialAuthUpdateRequest;
use Modules\Auth\App\Http\Requests\SocialiteAuthRequest;
use Modules\Auth\Services\SocialAuthService;
use Modules\Customer\App\Models\Customer;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;

class SocialiteController extends Controller
{
    private SocialAuthService $socialAuth;

    public function __construct(SocialAuthService $socialAuth)
    {
        $this->socialAuth = $socialAuth;
    }

    public function index(SocialiteAuthRequest $request)
    {
        LogHelper::debug($request->all());
        return $this->socialAuth->check($request);
    }

    public function update(SocialAuthUpdateRequest $request)
    {
        LogHelper::debug($request->all());
       return $this->socialAuth->update($request);
    }

    public function info(Request $request, $profileId)
    {
        LogHelper::debug($request->all());
        return $this->socialAuth->info($profileId);
    }

    public function destroy(Request $request, Customer $customer)
    {
        return $this->socialAuth->delete($customer);
    }
}
