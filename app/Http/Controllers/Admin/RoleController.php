<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
class RoleController extends MasterController
{
    function __construct(Role $model)
    {
//        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
//        $this->middleware('permission:role-create', ['only' => ['create','store']]);
//        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
        parent::__construct();

    }

    public function index()
    {
        $rows = Role::orderBy('id','DESC')->latest()->get();
        return view('Dashboard.role.index', compact('rows'));
    }

    public function create()
    {
        $permission = Permission::get();
        return view('Dashboard.role.create',compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('admin.roles.index')
            ->with('success','Role created successfully');
    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
        return view('Dashboard.role.show',compact('role','rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('Dashboard.role.edit',compact('role','permission','rolePermissions'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('Dashboard.role.index')
            ->with('success','Role updated successfully');
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('Dashboard.role.index')
            ->with('success','Role deleted successfully');
    }
}
