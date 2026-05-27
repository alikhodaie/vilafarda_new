<?php

namespace App\Http\Controllers\Admin\Article;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Article\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Article\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleCategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('indexArticle', Category::class);

        $categories = Category::query()->article()->search()->latest()->withCount('articles')->paginate(10)->appends($request->all());
        return view('admin.articles.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('createArticle', Category::class);

        return view('admin.articles.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            DB::beginTransaction();

            Category::query()->create([
                'section' => Category::ARTICLE,
                'title' => $request->get('title'),
            ]);

            DB::commit();
            return redirect()->route('admin.articles.categories.index')->with('success', __('text.success.create category', ['title' => $request->get('title')]));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(Category $category)
    {
        $this->authorize('updateArticle', $category);

        return view('admin.articles.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            DB::beginTransaction();

            $category->update([
                'title' => $request->get('title'),
            ]);

            DB::commit();
            return redirect()->route('admin.articles.categories.index')->with('success', __('text.success.update category', ['title' => $category->title]));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Category $category)
    {
        $this->authorize('deleteArticle', $category);

        try {
            DB::beginTransaction();

            $category->delete();

            DB::commit();
            return redirect()->route('admin.articles.categories.index')->with('success', __('text.success.delete category'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
