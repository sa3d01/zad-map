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
//        $this->middleware('permission:roles');
        parent::__construct();
    }

    public function index()
    {
        $rows = Role::where('guard_name','admin')->orderBy('id','DESC')->latest()->get();
        return view('Dashboard.role.index', compact('rows'));
    }

    public function create()
    {
        $permission = Permission::all();
        return view('Dashboard.role.create',compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        $role = Role::create(['name' => $request->input('name')]);
        foreach ($request['permission'] as $permission_id){
            $permission=Permission::find($permission_id);
            $role->givePermissionTo($permission);
        }
       // $role->syncPermissions($request->input('permission'));
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
        $role->syncPermissions(Permission::whereIn('id',$request['permission'])->pluck('name'));
        return redirect()->route('admin.roles.index')
            ->with('success','Role updated successfully');
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('Dashboard.role.index')
            ->with('success','Role deleted successfully');
    }
}
