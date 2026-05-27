<?php

namespace App\Http\Controllers\Admin\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Home\Variable\StoreRequest;
use App\Http\Requests\Admin\Home\Variable\UpdateRequest;
use App\Models\Variable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeVariableController extends Controller
{
    public function index(Request $request)
    {
        cache()->delete(Variable::CACHE_KEY);

        $this->authorize('index', Variable::class);

        $variables = Variable::query()->search()->latest()->paginate(10)->appends($request->all());
        return view('admin.homes.variables.index', compact(['variables']));
    }

    public function create()
    {
        $this->authorize('create', Variable::class);

        return view('admin.homes.variables.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $variable = Variable::query()->create([
                'title' => $request->get('title'),
                'place_holder' => $request->get('placeholder'),
                'type' => $request->get('type'),
                'input_type' => $request->get('input_type'),
            ]);

            if (in_array($request->get('input_type'), [Variable::SELECT, Variable::CHECK_BOX])){
                foreach ($request->get('options') as $option){
                    $variable->syncOption($option['name']);
                }
            }

            cache()->delete(Variable::CACHE_KEY);
            DB::commit();
            return redirect()->route('admin.homes.variables.index')->with('success', __('text.success.create_variable', ['title' => $variable->title]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(Variable $variable)
    {
        $this->authorize('update', $variable);

        return view('admin.homes.variables.edit', compact(['variable']));
    }

    public function update(UpdateRequest $request, Variable $variable)
    {
        try {
            DB::beginTransaction();

            $variable->update([
                'title' => $request->get('title'),
                'place_holder' => $request->get('placeholder'),
                'type' => $request->get('type'),
                'input_type' => $request->get('input_type'),
            ]);

            if (in_array($request->get('input_type'), [Variable::SELECT, Variable::CHECK_BOX])){
                $options = collect($request->get('options'));
                foreach ($options as $option){
                    $variable->syncOption($option['name'], $option['id']);
                }

                $variable->options()->whereNotIn('id', $options->pluck('id'))->delete();
            }
            elseif ($variable->options()->exists()) {
                $variable->options()->delete();
            }

            cache()->delete(Variable::CACHE_KEY);
            DB::commit();
            return redirect()->route('admin.homes.variables.index')->with('success', __('text.success.update_variable', ['title' => $variable->title]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Variable $variable)
    {
        $this->authorize('delete', $variable);

        try {
            DB::beginTransaction();

            $variable->delete();

            cache()->delete(Variable::CACHE_KEY);
            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete_variable', ['title' => $variable->title]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
