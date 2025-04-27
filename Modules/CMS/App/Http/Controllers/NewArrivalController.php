<?php

namespace Modules\CMS\App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class NewArrivalController extends Controller
{
    public function index()
    {
        return view('cms::new-arrival.index');
    }

    public function create()
    {
        return view('cms::create');
    }

    public function store(Request $request): RedirectResponse
    {
        //
    }

    public function show($id)
    {
        return view('cms::show');
    }

    public function edit($id)
    {
        return view('cms::edit');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
