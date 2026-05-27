<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Consultant\StoreRequest;
use App\Http\Requests\Admin\Consultant\UpdateRequest;
use App\Models\Consultant;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConsultantController extends Controller
{
    public function index()
    {
        $this->authorize('index', Consultant::class);

        $consultants = Consultant::getFromCache();
        return view('admin.consultants.index', compact('consultants'));
    }

    public function create()
    {
        $this->authorize('create', Consultant::class);

        return view('admin.consultants.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $image = $request->file('image')->store(Consultant::IMAGE_PATH);
            Consultant::query()->create([
                'name' => $request->get('name'),
                'image' => basename($image),
                'province_id' => $request->get('province'),
                'city_id' => $request->get('city'),
                'phone_number' => $request->get('phone_number'),
                'whatsapp_number' => $request->get('whatsapp_number'),
                'whatsapp_default_message' => $request->get('whatsapp_default_message'),
            ]);

            DB::commit();
            return redirect()->route('admin.consultants.index')->with('success', __('text.success.create_consultant', ['name' => $request->get('name')]));
        }
        catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(Consultant $consultant)
    {
        $this->authorize('update', $consultant);

        return view('admin.consultants.edit', compact('consultant'));
    }

    public function update(UpdateRequest $request, Consultant $consultant)
    {
        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->get('name'),
                'province_id' => $request->get('province'),
                'city_id' => $request->get('city'),
                'phone_number' => $request->get('phone_number'),
                'whatsapp_number' => $request->get('whatsapp_number'),
                'whatsapp_default_message' => $request->get('whatsapp_default_message'),
            ];
            if ($request->filled('image')){
                Storage::delete(Consultant::IMAGE_PATH.$consultant->image);

                $image = $request->file('image')->store(Consultant::IMAGE_PATH);
                $data['image'] = basename($image);
            }
            $consultant->update($data);

            DB::commit();
            return redirect()->route('admin.consultants.index')->with('success', __('text.success.update_consultant', ['name' => $consultant->name]));
        }
        catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Consultant $consultant)
    {
        try {
            DB::beginTransaction();

            $consultant->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete_consultant', ['name' => $consultant->name]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
