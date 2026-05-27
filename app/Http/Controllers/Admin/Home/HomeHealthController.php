<?php

namespace App\Http\Controllers\Admin\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Home\Health\StoreRequest;
use App\Http\Requests\Admin\Home\Health\UpdateRequest;
use App\Models\Health;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeHealthController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Health::class);

        $healths = Health::query()->latest()->search()->paginate(10)->appends($request->all());

        return view('admin.homes.healths.index', compact(['healths']));
    }

    public function create()
    {
        $this->authorize('create', Health::class);

        return view('admin.homes.healths.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            Health::query()->create(['title' => $request->get('title')]);

            DB::commit();
            return redirect()->route('admin.homes.healths.index')->with('success', __('text.success.create_health'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __METHOD__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(Health $health)
    {
        $this->authorize('update', $health);

        return view('admin.homes.healths.edit', compact(['health']));
    }

    public function update(Health $health, UpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $health->update(['title' => $request->get('title')]);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update_health'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __METHOD__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Health $health)
    {
        $this->authorize('delete', $health);

        try {
            DB::beginTransaction();

            $health->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete_health'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __METHOD__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
