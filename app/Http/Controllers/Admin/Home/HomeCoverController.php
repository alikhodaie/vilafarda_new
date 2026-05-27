<?php

namespace App\Http\Controllers\Admin\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Home\Image\UpdateCoverRequest;
use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeCoverController extends Controller
{
    public function update(UpdateCoverRequest $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->updateCover($request->file('file'));

            DB::commit();
            return true;
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function delete(Request $request, Home $home)
    {
        $this->authorize('update', $home);

        try {
            DB::beginTransaction();

            $home->deleteCover();

            DB::commit();
            return true;
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
