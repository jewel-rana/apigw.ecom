<?php

namespace Modules\Newsletter\App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        return view('newsletter::index');
    }

    public function destroy($id)
    {
        //
    }
}
