<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function form()
    {
        if (request()->is_mobile) {
            return view('main.auth.register-mobile');
        }
        return view('main.auth.register');
    }

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::query()->create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'mobile' => $request->get('mobile'),
                'password' => Hash::make($request->get('password')),
            ]);

            auth()->login($user, true);

            DB::commit();
            return redirect(RouteServiceProvider::HOME);
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
