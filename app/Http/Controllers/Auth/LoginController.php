<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
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


    public function loginAs(Request $request){
        $user = User::findOrFail($request->input('userId'));
        if($user){
            Auth::logout();
            Auth::login($user);
            $request->session()->regenerate();

            return redirect('/admin/dashboard');
        }
    }

    public function destroy()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
