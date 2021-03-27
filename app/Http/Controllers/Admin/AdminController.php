<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Dashboard\Auth\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends MasterController
{
    public function __construct(User $model)
    {
        $this->model = $model;
//        $this->middleware('permission:view-admins', ['only' => ['index']]);
//        $this->middleware('permission:add-admins', ['only' => ['create']]);
//        $this->middleware('permission:edit-admins', ['only' => ['show','activate']]);
        parent::__construct();
    }

    public function profile()
    {
        $row = Auth::user();
        return View('Dashboard.auth.profile', [
            'row' => $row,
            'type' => 'admin',
            'action' => 'admin.update_profile',
            'title' => 'الملف الشخصى',
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request): object
    {
        $admin = Auth::user();
        $admin->update($request->validated());
        return redirect()->back()->with('updated', 'تم التعديل بنجاح');
    }

}
