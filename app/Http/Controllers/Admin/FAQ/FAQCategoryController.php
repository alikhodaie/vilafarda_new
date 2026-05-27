<?php

namespace App\Http\Controllers\Admin\FAQ;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FAQ\Category\StoreRequest;
use App\Http\Requests\Admin\FAQ\Category\UpdateRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FAQCategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('indexFAQ', Category::class);

        $categories = Category::query()->FAQ()->search()->latest()->withCount('faq')->paginate(10)->appends($request->all());
        return view('admin.faq.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('createFAQ', Category::class);

        return view('admin.faq.categories.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            Category::query()->create([
                'section' => Category::FAQ,
                'title' => $request->get('title'),
            ]);

            DB::commit();
            return redirect()->route('admin.faq.categories.index')->with('success', __('text.success.create category', ['title' => $request->get('title')]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(Category $category)
    {
        $this->authorize('updateFAQ', $category);

        return view('admin.faq.categories.edit', compact('category'));
    }

    public function update(UpdateRequest $request, Category $category)
    {
        try {
            DB::beginTransaction();

            $category->update([
                'title' => $request->get('title'),
            ]);

            DB::commit();
            return redirect()->route('admin.')->with('success', __('text.success.update category', ['title' => $category->title]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Category $category)
    {
        $this->authorize('deleteFAQ', $category);

        try {
            DB::beginTransaction();

            $category->faq()->delete();
            $category->delete();

            DB::commit();
            return redirect()->route('admin.faq.categories.index')->with('success', __('text.success.delete category'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
