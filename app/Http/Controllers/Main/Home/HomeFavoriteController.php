<?php

namespace App\Http\Controllers\Main\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Models\Home;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;

class HomeFavoriteController extends Controller
{
    public function store(Home $home)
    {
        try {
            DB::beginTransaction();

            $home->addToFavorite();

            DB::commit();
            return response()->json(true);
        }
        catch(AuthenticationException $e) {
            DB::rollBack();
            return response()->json(['message' => __('text.please_login')], 401);
        }
        catch(Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return response()->json(['message' => __('text.whoops')], 500);
        }
    }

    public function destroy(Home $home)
    {
        try {
            DB::beginTransaction();

            $home->removeFromFavorite();

            DB::commit();
            return response()->json(true);
        }
        catch(AuthenticationException $e) {
            DB::rollBack();
            return response()->json(['message' => __('text.please_login')], 401);
        }
        catch(Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return response()->json(['message' => __('text.whoops')], 500);
        }
    }
}
