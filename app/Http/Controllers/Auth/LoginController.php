<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    public function form()
    {
        if (request()->is_mobile) {
            return view('main.auth.login-mobile');
        }
        return view('main.auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = [
                'mobile'   => $request->get('mobile'),
                'password' => $request->get('password')
            ];

            if (auth()->attempt($credentials, $request->filled('remember_me'))){
                return redirect()->intended(url()->previous());
            }
        }
        catch (\Exception $e){
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }

        return redirect()->back()->with('danger', __('auth.failed'));
    }

    public function logout()
    {
        try {
            auth()->logout();

            return redirect()->route('main.index');
        }
        catch (\Exception $e){
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
