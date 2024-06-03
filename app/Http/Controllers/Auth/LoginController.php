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

                $name = auth()->user()->name;
                activity("User")
                    ->event('Login')
                    ->performedOn(auth()->user())
                    ->causedBy(auth()->user())
                    ->log("Login $name");

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


            $name = auth()->user()->name;
            Auth::logout();
            Auth::login($user);
            $request->session()->regenerate();

            $loginAs = auth()->user()->name;
            activity("User")
                ->event('Login As')
                ->performedOn(auth()->user())
                ->causedBy(auth()->user())
                ->log("Login $name Login As $loginAs");

            return redirect('/admin/dashboard');
        }
    }

    public function destroy()
    {
        $name = auth()->user()->name;
        activity('User')
            ->event('Logout')
            ->performedOn(auth()->user())
            ->causedBy(auth()->user())
            ->log(" Logout $name");

        Auth::logout();
        return redirect()->route('login');
    }
}
