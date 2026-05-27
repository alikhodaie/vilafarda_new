<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Comment\StoreRequest;
use App\Http\Requests\Admin\Comment\UpdateCommentStatusRequest;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Home;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Comment::class);

        $comments = Comment::query()->with('commentable')->orderBy('status')->latest()->search()->paginate(10)->appends($request->all());
        return view('admin.comments.index', compact('comments'));
    }

    public function create()
    {
        $this->authorize('create', Comment::class);

        return view('admin.comments.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $model = match ($request->get('type')) {
                'home' => Home::query()->find($request->get('home')),
                'article' => Article::query()->find($request->get('article')),
                default => null
            };
            $user = User::query()->find($request->get('user'));

            if ($model)
            {
                $comment = $model->addComment(
                    $request->get('comment'),
                    $user->email,
                    $user->full_name,
                    null,
                    $request->get('score'),
                    $user->id,
                );

                $comment->update(['status' => $request->get('status')]);
            }

            DB::commit();
            return redirect()->route('admin.comments.index')->with('success', __('text.success.create comment'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }


    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);

        return view('admin.comments.edit', compact('comment'));
    }

    public function update(UpdateCommentStatusRequest $request, Comment $comment)
    {
        try {
            DB::beginTransaction();

            $comment->update(['status' => $request->get('status')]);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update status'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        try {
            DB::beginTransaction();

            $comment->delete();

            DB::commit();
            return redirect()->route('admin.comments.index')->with('success', __('text.success.delete comment'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
