<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MediaController extends Controller
{
    public function index()
    {
        return view('media::index');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('media::show');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {

    }
}
