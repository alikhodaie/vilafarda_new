<?php

namespace App\Http\Controllers\Api;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 


class CommentController extends Controller
{
    public function comment(Request $request)
    {
        $comments = Comment::query()
            ->with(['user', 'commentable'])
            ->whereHas('user')
            ->active()
            ->latest()
            ->take(6)
            ->get()
            ->map(fn (Comment $comment) => $comment->toIndexApiArray())
            ->values();

        return response()->json(['data' => $comments]);
    }
}
