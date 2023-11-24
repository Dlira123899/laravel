<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.dashboard', ['name' => $request->user()->name]);
    }

    public function create(Request $request)
    {
        return view('admin.product', ['name' => $request->user()->name]);
    }
}
