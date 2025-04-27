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
use Modules\Purchase\Enums\PurchaseStatusEnum;
use Modules\Purchase\Http\Requests\PurchaseCreateRequest;
use Modules\Purchase\Http\Requests\PurchaseItemUpdateRequest;
use Modules\Purchase\Http\Requests\PurchaseUpdateRequest;
use Modules\Provider\Entities\Provider;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Repositories\PurchaseItemRepositoryInterface;
use Modules\Purchase\Repositories\PurchaseRepositoryInterface;

class PurchaseItemService
{
    private PurchaseItemRepositoryInterface $purchaseItemRepository;

    public function __construct(PurchaseItemRepositoryInterface $purchaseItemRepository)
    {
        $this->purchaseItemRepository = $purchaseItemRepository;
    }
    public function all()
    {
        return Cache::remember('purchase-items', 3600, function () {
            return PurchaseItem::get();
        });
    }

    public function get($id)
    {
        return PurchaseItem::find($id);
    }

    public function getDataTables(Request $request): JsonResponse
    {
        return datatables()->eloquent(
            $this->purchaseItemRepository->with(['operator','bundle'])
                ->filter($request)
        )
            ->addColumn('actions', function (PurchaseItem $item) {
                $actions = '';
//                $actions .= '<a href="' . route('purchase.item.edit', $item). '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>';
//                $actions .= '<button data-action="' . route('purchase.item.destroy', $item). '" class="btn btn-sm btn-danger deleteBtn" data-type="item"><i class="fa fa-times"></i></button>';
                return $actions;
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function update(PurchaseItemUpdateRequest $request, PurchaseItem $item)
    {
        try {
            $item->operator_id = $request->operator_id;
            $item->bundle_id = $request->bundle_id;
            $item->unit_price = $request->unit_price;
            $item->quantity = $request->quantity;
            $item->amount = $request->unit_price * $request->quantity;
            $item->save();

            $totalQuantity = PurchaseItem::where('purchase_id',$item->purchase_id)->sum('quantity');
            $totalAmount = PurchaseItem::where('purchase_id',$item->purchase_id)->sum('amount');

            Purchase::where('id',$item->purchase_id)->update([
                'quantity' => $totalQuantity,
                'amount' => $totalAmount,
            ]);

            return redirect()->route('purchase.show', [$item->purchase_id,'tab'=>'items'])->with(['status' => true, 'message' => __('Item updated successfully')]);

        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return redirect()->back()->withInput($request->all())->with(['status' => false, 'message' => __('Failed to update item')]);
        }
    }

    public function destroy(PurchaseItem $item)
    {
        try {
            if(Purchase::where('id',$item->purchase_id)->where('status','!=',PurchaseStatusEnum::PENDING)->count()){
                return redirect()->back()->with(['status' => true, 'message' => __('Item failed to delete')]);
            }
            $item->delete();

            $totalQuantity = PurchaseItem::where('purchase_id',$item->purchase_id)->sum('quantity');
            $totalAmount = PurchaseItem::where('purchase_id',$item->purchase_id)->sum('amount');

            Purchase::where('id',$item->purchase_id)->update([
                'quantity' => $totalQuantity,
                'amount' => $totalAmount,
            ]);
            return redirect()->back()->with(['status' => true, 'message' => __('Item delete successful')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return redirect()->back()->with(['status' => false, 'message' => __('Failed to delete item')]);
        }
    }

    public function getSuggestions(Request $request): JsonResponse
    {
        try {
            $data = PurchaseItem::all()
                ->filter(function ($item) use ($request) {
                    $matched = true;
                    if($request->filled('purchase_id')) {
                        $matched = $item->purchase_id == $request->input('purchase_id');
                    }
                    return $matched;
                })
                ->map(function ($item, $key) {
                    return [
                        'id' => $item->id,
                        'text' => $item->bundle->name . ' - ' . $item->quantity . ' (' . $item->amount . ')'
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return response()->json(['message' => 'No data!']);
        }
    }

    public function getItemOperator(Request $request): JsonResponse
    {
        try {
            $data = PurchaseItem::all()
                ->filter(function ($item) use ($request) {
                    $matched = true;
                    if($request->filled('purchase_id')) {
                        $matched = $item->purchase_id == $request->input('purchase_id');
                    }
                    return $matched;
                })
                ->map(function ($provider, $key) {
                    return [
                        'id' => $provider->id,
                        'text' => $provider->amount
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return response()->json(['message' => 'No data!']);
        }
    }

    public function getItemBundle(Request $request): JsonResponse
    {
        try {
            $data = PurchaseItem::all()
                ->filter(function ($item) use ($request) {
                    $matched = true;
                    if($request->filled('purchase_id')) {
                        $matched = $item->purchase_id == $request->input('purchase_id');
                    }
                    return $matched;
                })
                ->map(function ($provider, $key) {
                    return [
                        'id' => $provider->id,
                        'text' => $provider->amount
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return response()->json(['message' => 'No data!']);
        }
    }
}
