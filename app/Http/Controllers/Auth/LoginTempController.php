<?php

namespace App\Http\Controllers\Auth;

use App\Classes\SMS;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginTempRequest;
use App\Http\Requests\Auth\LoginTempSendRequest;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class LoginTempController extends Controller
{
    public function form()
    {
        if (request()->is_mobile) {
            return view('main.auth.login-temp-mobile');
        }
        return view('main.auth.login-temp');
    }

    public function send(LoginTempSendRequest $request)
    {
        if (VerificationToken::tooManyAttempt($request->get('mobile'), VerificationToken::LOGIN)){
            throw ValidationException::withMessages([
                'error' => __('passwords.throttle', ['attribute' => VerificationToken::MAX_ATTEMPT_IN_DAY])
            ]);
        }

        $item = VerificationToken::generateToken($request->get('mobile'), VerificationToken::LOGIN);
        SMS::sendPattern($request->get('mobile'), '120268', [[
            'name' => 'CODE',
            'value' => $item->token,
        ]]);

        return redirect(URL::temporarySignedRoute(
            'main.login.temp', now()->addMinutes(VerificationToken::EXPIRE_MINUTES), ['value' => $request->get('mobile')]
            ))->with('success', __('text.success.code_sent'));
    }

    public function loginForm()
    {
        if (! request()->hasValidSignature()){
            return redirect()->route('main.login.temp.send')->with('danger', 'زمان شما به اتمام رسید. لطفا دوباره تلاش کنید!');
        }

        if (request()->is_mobile) {
            return view('main.auth.login-temp-verify-mobile');
        }
        return view('main.auth.login-temp-verify');
    }

    public function login(LoginTempRequest $request)
    {
        if (! $request->hasValidSignature()){
            return redirect()->route('main.login.temp.send')->with('danger', 'زمان شما به اتمام رسید. لطفا دوباره تلاش کنید!');
        }

        $item = VerificationToken::validToken($request->get('value'), VerificationToken::LOGIN);
        if ($item->token === $request->get('code')){
            $user = User::query()->where('mobile', $request->get('value'))->firstOrFail();
            $item->delete();
            auth()->login($user);

            return redirect()->intended(url()->previous());
        }

        throw ValidationException::withMessages([
            'code' => __('validation.in', ['attribute' => __('title.code')])
        ]);
    }
}
