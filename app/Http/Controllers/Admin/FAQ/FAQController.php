<?php

namespace App\Http\Controllers\Admin\FAQ;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FAQ\StoreRequest;
use App\Http\Requests\Admin\FAQ\UpdateRequest;
use App\Models\Category;
use App\Models\FAQ;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FAQController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', FAQ::class);

        $faq = FAQ::query()->search()->latest()->with('categories')->paginate(10)->appends($request->all());
        return view('admin.faq.index', compact('faq'));
    }

    public function create()
    {
        $this->authorize('create', FAQ::class);

        $categories = Category::query()->FAQ()->latest()->get();
        return view('admin.faq.create', compact('categories'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $faq = FAQ::query()->create([
                'question' => $request->get('question'),
                'answer' => $request->get('answer'),
                'sort' => $request->get('sort')
            ]);

            $faq->categories()->attach($request->get('category'));

            DB::commit();
            return redirect()->route('admin.faq.index')->with('success', __('text.success.create_faq', ['question' => $faq->question]));
        }
        catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(FAQ $faq)
    {
        $this->authorize('update', $faq);

        $categories = Category::query()->FAQ()->latest()->get();
        return view('admin.faq.edit', compact(['faq', 'categories']));
    }

    public function update(UpdateRequest $request, FAQ $faq)
    {
        try {
            DB::beginTransaction();

            $faq->update([
                'question' => $request->get('question'),
                'answer' => $request->get('answer'),
                'sort' => $request->get('sort')
            ]);

            $faq->categories()->sync($request->get('category'));

            DB::commit();
            return redirect()->route('admin.faq.index')->with('success', __('text.success.update_faq', ['question' => $faq->question]));
        }
        catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(FAQ $faq)
    {
        $this->authorize('delete', $faq);

        try {
            DB::beginTransaction();

            $faq->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete_faq', ['question' => $faq->question]));
        }
        catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
