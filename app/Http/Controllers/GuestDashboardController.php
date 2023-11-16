<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestDashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('guest', ['name' => $request->user()->name]);
    }
}
