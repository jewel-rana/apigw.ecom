<?php

namespace Modules\Region\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Region\Services\TimeZoneService;

class TimeZoneController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    private TimeZoneService $timeZoneService;

    public function __construct(TimeZoneService $timeZoneService)
    {
        $this->timeZoneService = $timeZoneService;
    }

    public function index()
    {
        return view('region::index');
    }

    public function create()
    {
        return view('region::create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('region::show');
    }

    public function edit($id)
    {
        return view('region::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function suggestion(Request $request): JsonResponse
    {
        return $this->timeZoneService->suggestion($request);
    }
}
