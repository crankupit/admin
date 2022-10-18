<?php

namespace CrankUpIT\Admin\Http\Controllers;

use Illuminate\Routing\Controller;

class AdminSessionController extends Controller
{
    public function index()
    {
        return view('admin::login');
    }
}
