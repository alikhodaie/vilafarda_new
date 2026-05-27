<?php

namespace App\Http\Controllers\Dashboard\Profile;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Profile\UpdateProfileRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        if ($request->is_mobile ?? false) {
            return view('dashboard.profile.edit-mobile');
        }

        return view('dashboard.profile.edit');
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = [
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'mobile' => $request->get('mobile')
            ];

            if ($request->filled('password')){
                $data['password'] = Hash::make($request->get('password'));
            }

            if ($request->get('email') !== auth()->user()->email){
                $data['email_verified_at'] = null;
            }
            if ($request->get('mobile') !== auth()->user()->mobile){
                $data['mobile_verified_at'] = null;
            }

            auth()->user()->update($data);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update profile'));
        }
        catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
