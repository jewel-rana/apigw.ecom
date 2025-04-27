<?php

namespace Modules\Setting\Http\Controllers;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Option;
use Modules\Setting\OptionServiceInterface;

class SettingController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    private OptionServiceInterface $option;

    public function __construct(OptionServiceInterface $optionService)
    {
        $this->option = $optionService;
    }

    public function index(Request $request)
    {
        $tab = $request->input('tab', 'general');
        return view('setting::index', compact('tab'))->withTitle('Settings');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $this->option->save($request->except('tab', '_token', 'id'), $request->tab);
            session()->flash('success', 'Settings update successfully');
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
        }

        return redirect()->route('setting.index', ['tab' => $request->tab]);
    }

    public function _option_exist($key): bool
    {
        return (bool)Option::where(['field' => $key])->first();
    }

    public function suggestions(Request $request): JsonResponse
    {
        try {
            $data = collect(config('setting.attributes'))
                ->filter(function ($item) use ($request) {
                    $matched = true;
                    if ($request->has('term')) {
                        $matched = CommonHelper::matchText(CommonHelper::beautifyText($item), $request->input('term'));
                    }

                    if($request->filled('lang')) {

                    }

                    return $matched;
                })
                ->map(function ($item, $key) {
                    return [
                        'id' => $item,
                        'text' => CommonHelper::beautifyText($item)
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'No data!', 'results' => []]);
        }
    }
}
