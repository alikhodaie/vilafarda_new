<?php

namespace App\Http\Controllers\Admin\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Home\Option\StoreHomeOptionRequest;
use App\Http\Requests\Admin\Home\Option\UpdateHomeOptionRequest;
use App\Models\Option;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeOptionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Option::class);

        $options = Option::query()->latest()->search()->paginate(10)->appends($request->all());
        return view('admin.homes.options.index', compact('options'));
    }

    public function create()
    {
        return view('admin.homes.options.create');
    }

    public function store(StoreHomeOptionRequest $request)
    {
        try {
            DB::beginTransaction();

            Option::query()->create([
                'title' => $request->get('title'),
                'icon_type' => $request->get('icon_type'),
                'icon' => $this->resolveIconValue($request),
            ]);

            DB::commit();
            return redirect()->route('admin.homes.options.index')->with('success', __('text.success.create option', ['title' => $request->get('title')]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(Option $option)
    {
        $this->authorize('update', $option);

        return view('admin.homes.options.edit', compact(['option']));
    }

    public function update(UpdateHomeOptionRequest $request, Option $option)
    {
        try {
            DB::beginTransaction();

            $data = [
                'title' => $request->get('title'),
                'icon_type' => $request->get('icon_type'),
            ];

            if ($request->get('icon_type') === Option::ICON_TYPE_FONT) {
                if (! $option->isFontIcon()) {
                    $option->deleteIcon();
                }
                $data['icon'] = $request->get('icon_class');
            } elseif ($request->hasFile('icon')) {
                if (! $option->isFontIcon()) {
                    $option->deleteIcon();
                }
                $icon = $request->file('icon')->store(Option::ICON_PATH);
                $data['icon'] = basename($icon);
            }

            $option->update($data);

            DB::commit();
            return redirect()->route('admin.homes.options.index')->with('success', __('text.success.update option', ['title' => $option->title]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Option $option)
    {
        $this->authorize('delete', $option);

        try {
            DB::beginTransaction();

            $option->deleteIcon();
            $option->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete option', ['title' => $option->title]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    private function resolveIconValue(Request $request): string
    {
        if ($request->get('icon_type') === Option::ICON_TYPE_FONT) {
            return $request->get('icon_class');
        }

        $icon = $request->file('icon')->store(Option::ICON_PATH);

        return basename($icon);
    }
}
