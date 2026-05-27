<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Role::class);

        $roles = Role::query()->oldest();

        if ($request->filled('id')){
            $roles->where('id', $request->get('id'));
        }
        if ($request->filled('title')){
            $roles->where('fa_name', 'LIKE', '%'.$request->get('title').'%');
        }

        $roles = $roles->paginate(10)->appends($request->all());
        return view('admin.roles.index', compact(['roles']));
    }

    public function create()
    {
        $this->authorize('create', Role::class);
        $group = Permission::all()->groupBy('fa_group');

        return view('admin.roles.create', compact(['group']));
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            DB::beginTransaction();

            $role = Role::query()->create([
                'name' => Str::random(10),
                'fa_name' => $request->get('title')
            ]);

            $role->syncPermissions($request->get('permissions'));

            DB::commit();
            return redirect()->route('admin.roles.edit', $role->id)->with('success', __('text.success.create role', ['name' => $role->name]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(Role $role)
    {
        $this->authorize('update', $role);
        $group = Permission::all()->groupBy('fa_group');

        return view('admin.roles.edit', compact(['role', 'group']));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            DB::beginTransaction();

            $role->update(['fa_name' => $request->get('title')]);
            $role->syncPermissions($request->get('permissions'));

            DB::commit();
            return redirect()->route('admin.roles.index')->with('success', __('text.success.update role', ['name' => $role->name]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        try {
            DB::beginTransaction();

            $role->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete role', ['name' => $role->name]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
