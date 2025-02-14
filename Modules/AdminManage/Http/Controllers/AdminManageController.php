<?php

namespace Modules\AdminManage\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\AdminManage\Entities\Admin;
use Modules\AdminManage\Http\Requests\AdminUpdateRequest;
use Modules\AdminManage\Http\Requests\StoreAdminRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminManageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin']);
    }

    public function new_user()
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('adminmanage::backend.add-new-user', compact('roles'));
    }

    public function new_user_add(StoreAdminRequest $request)
    {
        $admin = Admin::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'image' => $request->image,
            'password' => Hash::make($request->password),
        ]);

        $admin->assignRole($request->role);

        return redirect()->back()->with(['msg' => __('New Admin Added'), 'type' => 'success']);
    }

    public function all_user()
    {
        $all_user = Admin::with('roles')->get()->except(Auth::id());

        return view('adminmanage::backend.all-user')->with(['all_user' => $all_user]);
    }

    public function user_edit($id)
    {
        $admin = Admin::findOrFail($id);
        $roles = Role::pluck('name', 'name')->all();
        $adminRole = $admin->roles->pluck('name', 'name')->all();

        return view('adminmanage::backend.edit-user', compact('roles', 'adminRole', 'admin'));
    }

    public function user_update(AdminUpdateRequest $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'image' => $request->image,
        ];

        $admin = Admin::findOrFail($request->user_id);
        $admin->update($data);
        DB::table('model_has_roles')->where('model_id', $admin->id)->delete();
        $admin->assignRole($request->role);

        return redirect()->back()->with(['msg' => __('Admin Details Updated'), 'type' => 'success']);
    }

    public function new_user_delete(Request $request, $id)
    {
        Admin::findOrFail($id)->delete();

        return redirect()->back()->with(['msg' => __('Admin Deleted'), 'type' => 'danger']);
    }

    public function user_password_change(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = Admin::findOrFail($request->ch_user_id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with(['msg' => __('Password Change Success..'), 'type' => 'success']);
    }

    public function all_admin_role()
    {
        $roles = Role::all();

        return view('adminmanage::backend.role.index', compact('roles'));
    }

    public function new_admin_role_index()
    {
        $permissions = Permission::orderBy('menu_name', 'asc')->get();
        $permissions = $permissions->groupBy('menu_name');

        return view('adminmanage::backend.role.create', compact('permissions'));
    }

    public function store_new_admin_role(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:roles,name',
        ]);
        $role = Role::create(['name' => $request->name, 'guard_name' => 'admin']);
        $role->syncPermissions($request->permission);

        return back()->with(FlashMsg::settings_update('New Role Created'));
    }

    public function edit_admin_role($id)
    {
        $role = Role::find($id);
        $permissions = Permission::orderBy('menu_name', 'asc')->get();
        $permissions = $permissions->groupBy('menu_name');
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $id)
            ->select('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('adminmanage::backend.role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update_admin_role(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'permission' => 'required|array',
        ]);
        $role = Role::find($request->id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->permission);

        return back()->with(FlashMsg::settings_update('Role Updated'));
    }

    public function delete_admin_role($id)
    {
        Role::findOrfail($id)->delete();

        return back()->with(FlashMsg::settings_delete('Role Deleted'));
    }
}
