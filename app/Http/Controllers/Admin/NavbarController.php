<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Navbar\NavbarStoreRequest;
use App\Http\Requests\Admin\Navbar\NavbarUpdateRequest;
use App\Models\Navbar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NavbarController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Navbar::class);

        $items = Navbar::query()->with('parent');

        if ($request->filled('id')){
            $items->where('id', $request->get('id'));
        }
        if ($request->filled('title')){
            $items->where(function ($query) use ($request){
                foreach (explode(' ', $request->get('title')) as $title){
                    $query->orWhere('title', 'LIKE', '%'.$title.'%');
                }
            });
        }
        if ($request->filled('parent')){
            $items->where('parent_id', $request->get('parent'));
        }

        $items = $items->latest()->get();
        return view('admin.navbar.index', compact(['items']));
    }

    public function create()
    {
        $this->authorize('create', Navbar::class);

        $items = Navbar::all();
        return view('admin.navbar.create', compact(['items']));
    }

    public function store(NavbarStoreRequest $request)
    {
        $this->authorize('create', Navbar::class);

        try {
            DB::beginTransaction();

            Navbar::query()->create([
                'title' => $request->get('title'),
                'link'  => $request->get('link'),
                'sort' => $request->get('sort'),
                'parent_id' => $request->get('parent')
            ]);

            DB::commit();
            return redirect()->route('admin.navbar.index')->with('success', __('text.success.create item'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(Navbar $navbar)
    {
        $this->authorize('update', $navbar);

        $items = Navbar::all();
        return view('admin.navbar.edit', compact(['navbar', 'items']));
    }

    public function update(NavbarUpdateRequest $request, Navbar $navbar)
    {
        $this->authorize('update', $navbar);

        try {
            DB::beginTransaction();

            $navbar->update([
                'title' => $request->get('title'),
                'link'  => $request->get('link'),
                'sort' => $request->get('sort'),
                'parent_id' => $request->get('parent')
            ]);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update item'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Navbar $navbar)
    {
        $this->authorize('delete', $navbar);

        try {
            DB::beginTransaction();

            $navbar->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete item'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
