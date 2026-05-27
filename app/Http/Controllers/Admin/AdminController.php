<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\Admin\UpdateAdminRequest;
use App\Http\Requests\Admin\User\IndexUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(IndexUserRequest $request)
    {
        $this->authorize('adminIndex', User::class);

        $admins = User::query()->admin()->search()->paginate(10)->appends($request->all());
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $this->authorize('adminCreate', User::class);

        return view('admin.admins.create');
    }

    public function store(StoreAdminRequest $request)
    {
        try {
            DB::beginTransaction();

            $admin = User::query()->create([
                'is_admin'   => true,
                'first_name' => $request->get('first_name'),
                'last_name'  => $request->get('last_name'),
                'mobile'     => $request->get('mobile'),
                'email'      => $request->get('email'),
                'password'   => Hash::make($request->get('password')),
            ]);

            if (auth()->user()->can('adminAssignRole', User::class)) {
                $admin->assignRole($request->get('role'));
            }

            if ($request->hasFile('avatar')){
                $admin->updateAvatar($request->file('avatar'));
            }

            DB::commit();
            return redirect()->route('admin.admins.index')->with('success', __('text.success.create admin', ['name' => $admin->full_name]));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(User $admin)
    {
        $this->authorize('adminUpdate', $admin);

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(User $admin, UpdateAdminRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = [
                'first_name' => $request->get('first_name'),
                'last_name'  => $request->get('last_name'),
                'mobile'     => $request->get('mobile'),
                'email'      => $request->get('email')
            ];

            if ($request->filled('password')){
                $data['password'] = Hash::make($request->get('password'));
            }
            if (auth()->user()->can('adminBlock', $admin)){
                $data['is_block'] = $request->filled('blocked');
            }
            if ($data['email'] !== $admin->email){
                $data['email_verified_at'] = null;
            }
            if ($data['mobile'] !== $admin->mobile){
                $data['mobile_verified_at'] = null;
            }

            $admin->update($data);
            if ($request->hasFile('avatar')){
                $admin->updateAvatar($request->file('avatar'));
            }

            if (auth()->user()->can('adminUpdateRole', $admin)) {
                $admin->syncRoles($request->get('role'));
            }

            DB::commit();

            return redirect()->route('admin.admins.index')->with('success', __('text.success.update admin', ['name' => $admin->full_name]));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(User $admin)
    {
        $this->authorize('adminDelete', $admin);

        try {
            DB::beginTransaction();

            $admin->delete();

            DB::commit();
            return redirect()->route('admin.admins.index')->with('success', __('text.success.delete admin', ['name' => $admin->full_name]));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
