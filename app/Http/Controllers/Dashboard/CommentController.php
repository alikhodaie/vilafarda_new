<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Comment\StoreRequest;
use App\Models\Comment;
use App\Models\Home;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $comments = Comment::query()->where('user_id', '!=', auth()->id())->with('activeChildren')->withCount('children')->where('commentable_type', Home::class)->active()->whereHas('commentable', function (Builder $query){
            $query->where('user_id', auth()->id());
        })->latest()->paginate(10);

        if ($request->is_mobile ?? false) {
            return view('dashboard.comments.index-mobile', compact('comments'));
        }

        return view('dashboard.comments.index', compact('comments'));
    }

    public function store(StoreRequest $request)
    {
        $comment = Comment::query()->active()->findOrFail($request->get('reply_id'));
        $home = $comment->commentable;
        $home = auth()->user()->homes()->findOrFail($home->id);

        try {
            DB::beginTransaction();

            $home->addComment($request->get('comment'), auth()->user()->email, auth()->user()->full_name, $request->get('reply_id'));

            DB::commit();
            return redirect()->back()->with('success', __('text.success.store_comment'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
