<?php

namespace App\Http\Controllers\Dashboard\Profile;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Profile\UpdateAvatarRequest;
use Illuminate\Support\Facades\DB;

class AvatarController extends Controller
{
    public function update(UpdateAvatarRequest $request)
    {
        try {
            DB::beginTransaction();

            auth()->user()->updateAvatar($request->file('avatar'));

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update avatar'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy()
    {
        try {
            DB::beginTransaction();

            auth()->user()->removeAvatar();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete avatar'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
