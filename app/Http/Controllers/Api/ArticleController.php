<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        // گرفتن مقدار limit از کوئری (پیشفرض 10 تا)
        $limit = $request->get('limit', 10);

        // ساخت کوئری بدون scopeSearch
        $articles = Article::query()
            ->with(['author', 'categories'])
            ->latest('id')
            ->paginate($limit);

        return response()->json($articles);
    }
}
