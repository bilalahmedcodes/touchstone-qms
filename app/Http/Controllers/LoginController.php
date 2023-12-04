<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
public function index()
    {
        return view('login');
    }

    public function login(LoginRequest $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'active' ])) {
            return redirect()->intended('home');
        } else {
            return redirect()->back()->with('error', 'Invalid Email or Password');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
