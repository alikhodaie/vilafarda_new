<?php

namespace App\Http\Controllers\Main\Article;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Main\Comment\StoreCommentRequest;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class ArticleCommentController extends Controller
{
    public function store(StoreCommentRequest $request, Article $article)
    {
        try {
            DB::beginTransaction();

            $article->addComment(
                $request->get('comment'),
                $request->get('email'),
                $request->get('name'),
                $request->get('reply_id')
            );

            DB::commit();
            return redirect()->back()->with('success', __('text.success.store_comment'));
        }
        catch(\Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
