<?php

namespace Modules\Setting\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Option;
use Modules\Setting\OptionServiceInterface;

class SettingController extends Controller
{
    private OptionServiceInterface $option;

    public function __construct(OptionServiceInterface $optionService)
    {
        $this->option = $optionService;
    }

    public function index()
    {
        $response = [];

        $options = Option::get()->groupBy('tab');
        foreach ($options as $tab => $option) {
            $response[$tab] = $option->map(function ($option) {
                return $option->only(['field', 'value']);
            });
        }
        return response()->success($response);
    }

    public function store(Request $request)
    {
        try {
            $this->option->save($request->except('tab', '_token', 'id'), $request->tab);
            return response()->success();
        } catch (\Exception $exception) {
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }
}
