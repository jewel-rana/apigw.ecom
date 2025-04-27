<?php

namespace Modules\Provider\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Provider\Entities\ProviderUser;
use Modules\Provider\Http\Requests\ProviderUserCreateRequest;
use Modules\Provider\Http\Requests\ProviderUserUpdateRequest;
use Modules\Provider\Services\ProviderUserService;

class ProviderUserController extends Controller
{
    private ProviderUserService $providerUserService;

    public function __construct(ProviderUserService $providerUserService)
    {
        $this->providerUserService = $providerUserService;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->providerUserService->getDataTable($request);
        }
        return view('provider::user.index')->with(['title' => 'Users']);
    }

    public function create(): View
    {
        return view('provider::user.create')->with(['title' => 'Add new user']);
    }

    public function store(ProviderUserCreateRequest $request): RedirectResponse
    {
        try {
            $user = $this->providerUserService->create($request->validated());
            return redirect()->route('provider.user.index', ['provider_id' => $user->provider_id])
                ->with(['status' => true, 'content' => __('Successful')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PROVIDER_USER_CREATE_EXCEPTION'
            ]);
            return redirect()->back()->withInput($request->all())
                ->with(['status' => false, 'content' => __('Internal server error!')]);
        }
    }

    public function show(ProviderUser $user): View
    {
        return view('provider::user.show', compact('user'))->with(['title' => 'Show user']);
    }

    public function edit(ProviderUser $user): View
    {
        return view('provider::user.edit', compact('user'))->with(['title' => 'Update user']);
    }

    public function update(ProviderUserUpdateRequest $request, $id): RedirectResponse
    {
        try {
            $user = $this->providerUserService->update($request->validated(), $id);
            return redirect()->route('provider.user.index', ['provider_id' => $user->provider_id])
                ->with(['status' => true, 'content' => __('Successful')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PROVIDER_USER_CREATE_EXCEPTION'
            ]);
            return redirect()->back()->with(['status' => false, 'content' => __('Internal server error!')]);
        }
    }

    public function destroy($id)
    {
        //
    }
}
