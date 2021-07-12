<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminsController extends MasterController
{
    function __construct(User $model)
    {
//        $this->middleware('permission:admins');
        parent::__construct();

    }
    public function index()
    {
        $rows = User::whereType('ADMIN')->get();
        return view('Dashboard.admin.index',compact('rows'));
    }

    public function create()
    {
        $roles = Role::where('guard_name','admin')->get();
        return view('Dashboard.admin.create',compact('roles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required'
        ]);
        $input = $request->all();
        $input['type']='ADMIN';
        $user = User::create($input);
        $role=Role::findById($request['role']);
        $user->syncRoles($role);
        return redirect()->route('admin.admins.index')
            ->with('success','Admin created successfully');
    }

    public function show($id)
    {
        $row = User::find($id);
        return view('Dashboard.admin.show',compact('row'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::where('guard_name','admin')->get();
        $userRole = $user->roles->pluck('id')->toArray();
        return view('Dashboard.admin.edit',compact('user','roles','userRole'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required'
        ]);
        $input = $request->all();
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $role=Role::findById($request['role']);
        $user->syncRoles($role);
        return redirect()->route('admin.admins.index')
            ->with('success','User updated successfully');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success','User deleted successfully');
    }
}
