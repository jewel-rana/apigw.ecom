<?php

namespace Modules\Provider\Services;

use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Provider\Http\Requests\ProviderCashDepositRequest;
use Modules\Provider\Repositories\Interfaces\ProviderDepositRepositoryInterface;
use Modules\Provider\Repositories\Interfaces\ProviderRepositoryInterface;

class ProviderCashService
{
    private ProviderDepositRepositoryInterface $cashRepository;
    private ProviderRepositoryInterface $providerRepository;

    public function __construct(
        ProviderDepositRepositoryInterface $cashRepository,
        ProviderRepositoryInterface        $providerRepository
    )
    {
        $this->cashRepository = $cashRepository;
        $this->providerRepository = $providerRepository;
    }

    public function getDataTables(Request $request): JsonResponse
    {
        return datatables()->eloquent(
            $this->cashRepository->with(['provider'])
                ->filter($request)
        )
            ->toJson();
    }

    public function create(ProviderCashDepositRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $amountInIqd = $request->input('amount');
                $provider = $this->providerRepository->find($request->input('provider_id'))
                    ->lockForUpdate()
                    ->first();
                $this->cashRepository->create($request->validated() +
                    [
                        'previous_balance' => $provider->balance,
                        'currency_rate' => 1,
                        'amount_iqd' => $amountInIqd,
                        'current_balance' => $provider->balance + $amountInIqd
                    ]);
                $provider->increment('balance', $amountInIqd);
            }, 2);
            return redirect()->route('provider.cash.index')
                ->with(['status' => true, 'message' => 'Deposit successful']);
        } catch (\Exception $exception) {
            dd($exception);
            LogHelper::exception($exception, [
                'keyword' => 'PROVIDER_DEPOSIT_EXCEPTION'
            ]);
            return redirect()->back()->withInput($request->all())
                ->with(['status' => false, 'message' => 'Internal server error!']);
        }
    }
}
