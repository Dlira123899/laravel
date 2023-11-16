<?php

namespace App\Http\Controllers;

use App\Models\User;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->session()->has('role')) {
            $role = '/' . $request->session()->get('role');
            return redirect($role);
        } else {
            return view('login');
        }
    }

    public function check(Request $request)
    {
        try {
            $username = $request->username;

            $user = User::where(['email' => $username])->first();
            if ($user && Hash::check($request->password, $user->password)) {

                $dashboard = $user->role;

                session([
                    'loggedin' => true,
                    'name' => $user->name,
                    'role' => $user->role,
                ]);
                return redirect($dashboard);
            } else {
                throw new Exception('Invalid Username and Password');
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
