<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role === 'admin') return redirect('/admin'); // Admin Dashboard

        if ($request->user()->role === 'guest') return redirect('/guest'); // Guest Dashboard
    }
}
