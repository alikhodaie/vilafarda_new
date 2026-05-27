<?php

namespace App\Http\Controllers\Main\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Main\Comment\StoreCommentRequest;
use App\Models\Home;
use Exception;
use Illuminate\Support\Facades\DB;

class HomeCommentController extends Controller
{
    public function store(StoreCommentRequest $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->addComment(
                $request->get('comment'),
                $request->get('email'),
                $request->get('name'),
                $request->get('reply_id'),
                $request->get('score')
            );

            DB::commit();
            return redirect()->back()->with('success', __('text.success.store_comment'));
        }
        catch(Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

}
