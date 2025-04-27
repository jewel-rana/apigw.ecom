<?php

namespace Modules\Setting\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Setting\App\Models\OptionAttribute;

class OptionAttributeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return datatables()->eloquent(
            OptionAttribute::query()
        )
            ->addColumn('actions', function ($item) {
                return "<button class='btn btn-danger deleteAttribute' data-action='" . route('setting.attribute.destroy', $item->id) . "'><i class='fa fa-times'></i></button>";
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function store(Request $request)
    {
        try {
            OptionAttribute::updateOrCreate(
                $request->only(['key', 'lang']),
                $request->only(['key', 'lang', 'value'])
            );
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'OPTION_ATTRIBUTE_STORE_EXCEPTION'
            ]);
            return response()->failed();
        }
    }

    public function destroy(OptionAttribute $attribute)
    {
        try {
            $attribute->delete();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'OPTION_ATTRIBUTE_DELETE'
            ]);
            return response()->failed();
        }
    }
}
