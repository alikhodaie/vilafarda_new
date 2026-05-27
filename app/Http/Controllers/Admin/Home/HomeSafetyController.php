<?php

namespace App\Http\Controllers\Admin\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Home\Safety\StoreRequest;
use App\Http\Requests\Admin\Home\Safety\UpdateRequest;
use App\Models\Safety;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeSafetyController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Safety::class);

        $safeties = Safety::query()->latest()->search()->paginate(10)->appends($request->all());

        return view('admin.homes.safeties.index', compact(['safeties']));
    }

    public function create()
    {
        $this->authorize('create', Safety::class);

        return view('admin.homes.safeties.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            Safety::query()->create([
                'title' => $request->get('title'),
                'placeholder' => $request->get('placeholder')
            ]);

            DB::commit();
            return redirect()->route('admin.homes.safeties.index')->with('success', __('text.success.create_safety', ['title' => $request->get('title')]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __METHOD__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(Safety $safety)
    {
        $this->authorize('update', $safety);

        return view('admin.homes.safeties.edit', compact(['safety']));
    }

    public function update(UpdateRequest $request, Safety $safety)
    {
        try {
            DB::beginTransaction();

            $safety->update([
                'title' => $request->get('title'),
                'placeholder' => $request->get('placeholder')
            ]);

            DB::commit();
            return redirect()->route('admin.homes.safeties.index')->with('success', __('text.success.update_safety', ['title' => $safety->title]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __METHOD__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Safety $safety)
    {
        $this->authorize('delete', $safety);

        try {
            DB::beginTransaction();

            $safety->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete_safety', ['title' => $safety->title]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __METHOD__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
