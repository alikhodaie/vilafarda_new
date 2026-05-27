<?php

namespace App\Http\Controllers\Admin\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Home\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Home\Category\UpdateCategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeCategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('indexHome', Category::class);

        $categories = Category::query()->home()->search()->latest()->withCount('homes')->paginate(10)->appends($request->all());
        return view('admin.homes.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('createHome', Category::class);

        return view('admin.homes.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            DB::beginTransaction();

            Category::query()->create([
                'section' => Category::HOME,
                'title' => $request->get('title'),
            ]);

            DB::commit();
            return redirect()->route('admin.homes.categories.index')->with('success', __('text.success.create category', ['title' => $request->get('title')]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(Category $category)
    {
        $this->authorize('updateHome', $category);

        return view('admin.homes.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            DB::beginTransaction();

            $category->update([
                'title' => $request->get('title'),
            ]);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update category'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Category $category)
    {
        $this->authorize('deleteHome', $category);

        try {
            DB::beginTransaction();

            $category->delete();

            DB::commit();
            return redirect()->route('admin.homes.categories.index')->with('success', __('text.success.delete category'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
