<?php

namespace Modules\Purchase\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Entities\PurchaseItem;
use Modules\Purchase\Http\Requests\PurchaseCreateRequest;
use Modules\Purchase\Http\Requests\PurchaseUpdateRequest;
use Modules\Provider\Entities\Provider;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Repositories\PurchaseRepositoryInterface;

class PurchaseService
{
    private PurchaseRepositoryInterface $purchaseRepository;

    public function __construct(PurchaseRepositoryInterface $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }
    public function all()
    {
        return Cache::remember('purchases', 3600, function () {
            return Purchase::get();
        });
    }

    public function get($id)
    {
        return Purchase::find($id);
    }

    public function getDataTables(Request $request): JsonResponse
    {
        return datatables()->eloquent(
            $this->purchaseRepository->with('provider')
        )
            ->addColumn('actions', function (Purchase $purchase) {
                $btns = "";
                if(CommonHelper::hasPermission(['purchase-show'])){
                    $btns .=  '<a href="' . route('purchase.show', $purchase->id) . '" class="btn btn-sm btn-default"><i class="fa fa-eye"></i></a>';
                }
                if(CommonHelper::hasPermission(['purchase-update'])){
                    $btns .= '<a href="' . route('purchase.edit', $purchase->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>';
                }

                return $btns;
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function store(PurchaseCreateRequest $request): RedirectResponse
    {
        try {

            DB::transaction(function () use ($request){
                $item = $request->input('item');
                $totalQuantity = 0;
                $totalAmount = 0;

                foreach ($item['operator_id'] as $index => $operator_id) {
                    $totalQuantity += $item['quantity'][$index];
                    $totalAmount += $item['amount'][$index];
                }

                $purchase = Purchase::create(array_filter($request->validated() + [
                    'quantity' => $totalQuantity,
                    'amount' => $totalAmount,
                    ]));

                foreach ($request->input('item.operator_id') as $index => $operator_id) {
                    $itemAmount = $item['unit_price'][$index] * $item['quantity'][$index];
                    $purchase->items()->save(new PurchaseItem([
                        'operator_id' => $operator_id,
                        'bundle_id' => $item['bundle_id'][$index] ?? null,
                        'unit_price' => $item['unit_price'][$index],
                        'quantity' => $item['quantity'][$index],
                        'amount' => $itemAmount,
                    ]));
                }

            });

            return redirect()->route('purchase.index')->with(['status' => true, 'message' => __('Purchase successfully created')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return redirect()->back()->withInput($request->all())->with(['status' => false, 'message' => __('Failed to create purchase')]);
        }
    }

    public function update(PurchaseUpdateRequest $request, Purchase $purchase)
    {
        try {
            DB::transaction(function () use ($request, $purchase){
                $items = $request->input('item');
                $totalQuantity = 0;
                $totalAmount = 0;

                foreach ($items['operator_id'] as $index => $operator_id) {
                    $totalQuantity += $items['quantity'][$index];
                    $totalAmount += $items['amount'][$index];
                }
                $purchase->update(array_filter($request->validated() + [
                        'quantity' => $totalQuantity,
                        'amount' => $totalAmount,
                    ]));

                $existingItems = $purchase->items->keyBy('id');

                foreach ($items['operator_id'] as $index => $operator_id) {
                    $itemAmount = $items['unit_price'][$index] * $items['quantity'][$index];

                    $purchaseItemData = [
                        'operator_id' => $items['operator_id'][$index],
                        'bundle_id' => $items['bundle_id'][$index] ?? null,
                        'unit_price' => $items['unit_price'][$index],
                        'quantity' => $items['quantity'][$index],
                        'amount' => $itemAmount,
                    ];

                    if (isset($items['id'][$index]) && $existingItems->has($items['id'][$index])) {
                        $existingItems[$items['id'][$index]]->update($purchaseItemData);
                        $existingItems->forget($items['id'][$index]);
                    } else {
                        $purchase->items()->create($purchaseItemData);
                    }
                }

                foreach ($existingItems as $item) {
                    $item->delete();
                }

            });
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return response()->failed();
        }
    }

    public function getSuggestions(Request $request): JsonResponse
    {
        try {
            $data = Purchase::filter($request)
                ->get()
                ->map(function ($purchase, $key) {
                    return [
                        'id' => $purchase->id,
                        'text' => $purchase->items->first()->operator->name . ' (' . $purchase->amount . ')'
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return response()->json(['message' => 'No data!']);
        }
    }

}
