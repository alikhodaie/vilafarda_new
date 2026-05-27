<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\IndexUserRequest;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(IndexUserRequest $request)
    {
        $users = User::query()->where('is_admin', false)->search()->latest()->paginate(10)->appends($request->all());

        return view('admin.users.index', compact(['users']));
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::query()->create([
                'first_name' => $request->get('first_name'),
                'last_name'  => $request->get('last_name'),
                'mobile'     => $request->get('mobile'),
                'email'      => $request->get('email'),
                'password'   => Hash::make($request->get('password')),
            ]);

            if ($request->hasFile('avatar')){
                $user->updateAvatar($request->file('avatar'));
            }

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', __('text.success.create user', ['name' => $user->full_name]));
        }
        catch (\Exception $e){
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('admin.users.edit', compact(['user']));
    }

    public function update(UpdateUserRequest $request, User $user)
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
            if (auth()->user()->can('block', $user)){
                $data['is_block'] = $request->filled('blocked');
            }
            if ($data['email'] !== $user->email){
                $data['email_verified_at'] = null;
            }
            if ($data['mobile'] !== $user->mobile){
                $data['mobile_verified_at'] = null;
            }
            $user->update($data);

            if ($request->hasFile('avatar')){
                $user->updateAvatar($request->file('avatar'));
            }

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', __('text.success.update user', ['name' => $user->full_name]));
        }
        catch (\Exception $e){
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete user', ['name' => $user->full_name]));
        }
        catch (\Exception $e){
            Error::catch($e, __CLASS__, __METHOD__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
