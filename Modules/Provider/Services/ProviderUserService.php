<?php

namespace Modules\Provider\Services;

use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Modules\Provider\Entities\ProviderUser;
use Modules\Provider\Repositories\Interfaces\ProviderUserRepositoryInterface;

class ProviderUserService
{
    private ProviderUserRepositoryInterface $providerUserRepository;

    public function __construct(ProviderUserRepositoryInterface $providerUserRepository)
    {
        $this->providerUserRepository = $providerUserRepository;
    }

    public function getDataTable(Request $request)
    {
        return datatables()->eloquent(
            $this->providerUserRepository->getModel()->query()
        )
            ->addColumn('actions', function (ProviderUser $user) {
                $str = '';
                $str .= "<a href='" . route('provider.user.edit', $user->id) . "' class='btn btn-primary'><i class='fa fa-edit'></i></a>";
                return $str;
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function create(array $data)
    {
        return $this->providerUserRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->providerUserRepository->update($data, $id);
    }
}
