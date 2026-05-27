<?php

namespace App\Http\Controllers\Main\Article;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::query()
            ->when($request->filled('search'), function($query) use ($request) {
                $query->where('title', 'LIKE', '%' . $request->get('search') . '%');
            })
            ->when($request->filled('author'), function($query) use ($request) {
                $query->where('author_id', $request->get('author'));
            })
            ->when($request->filled('category'), function($query) use ($request) {
                $query->whereHas('categories', function ($builder) use ($request) {
                    $builder->where('id', $request->get('category'));
                });
            })
            ->with(['author', 'categories'])
            ->latest()
            ->paginate(6)
            ->appends($request->all());

        if ($request->is_mobile ?? false) {
            return view('main.articles.index-mobile', compact('articles'));
        }

        return view('main.articles.index', compact('articles'));
    }

    public function show(Request $request, $id)
    {
        $article = Article::query()
            ->with(['author', 'tags',
                'activeComments', 'activeComments.user',
                'activeComments.activeChildren', 'activeComments.activeChildren.user',
                'activeComments.activeChildren.activeChildren', 'activeComments.activeChildren.activeChildren.user'])
            ->findOrFail($id);

        if ($request->is_mobile ?? false) {
            return view('main.articles.show-mobile', compact('article'));
        }

        return view('main.articles.show', compact('article'));
    }
}
