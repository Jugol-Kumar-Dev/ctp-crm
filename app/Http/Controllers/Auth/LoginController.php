<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return inertia('Auth/Login');
    }

    public function authenticate(Request $request)
    {

        try{
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);


            if (Auth::attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended();
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);

        }catch(\Exception $e){
            return back()->withErrors($e->getMessage());
        }

    }

    public function destroy()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
