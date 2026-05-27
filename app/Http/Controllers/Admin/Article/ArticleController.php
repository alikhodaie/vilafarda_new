<?php

namespace App\Http\Controllers\Admin\Article;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Article\StoreArticleRequest;
use App\Http\Requests\Admin\Article\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Article::class);

        $articles = Article::query()->search()->latest()->paginate(10)->appends($request->all());
        return view('admin.articles.index', compact(['articles']));
    }

    public function create()
    {
        $this->authorize('create', Article::class);

        return view('admin.articles.create');
    }

    public function store(StoreArticleRequest $request)
    {
        try {
            DB::beginTransaction();

            $article = Article::query()->create([
                'author_id' => auth()->id(),
                'title' => $request->get('title'),
                'slug'  => $request->get('slug'),
                'summary' => $request->get('summary'),
                'content' => $request->get('content'),
                'meta'  => $request->get('metas')
            ]);

            $article->updateImage($request->file('image'));

            $article->categories()->attach($request->get('category'));
            $article->tags()->attach($request->get('tags'));

            DB::commit();
            return redirect()->route('admin.articles.index')->with('success', __('text.success.create article', ['title' => $article->title]));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('error', __('text.whoops'));
        }
    }

    public function edit(Article $article)
    {
        $this->authorize('update', $article);

        return view('admin.articles.edit', compact('article'));
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        try {
            DB::beginTransaction();

            $data = [
                'title' => $request->get('title'),
                'slug'  => $request->get('slug'),
                'summary' => $request->get('summary'),
                'content' => $request->get('content'),
                'meta'  => $request->get('metas')
            ];

            $article->update($data);

            if ($request->hasFile('image')){
                $article->updateImage($request->file('image'));
            }

            $article->categories()->sync($request->get('category'));
            $article->tags()->sync($request->get('tags'));

            DB::commit();
            return redirect()->route('admin.articles.index')->with('success', __('text.success.update article', ['title' => $article->title]));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('error', __('text.whoops'));
        }
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        try {
            DB::beginTransaction();

            $article->delete();
            $article->deleteImage();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete article', ['title' => $article->title]));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('error', __('text.whoops'));
        }
    }
}
