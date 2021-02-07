<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\PasswordUpdateRequest;
use App\Http\Requests\Api\Auth\ProfileUpdateRequest;
use App\Http\Requests\Api\UploadImageRequest;
use App\Http\Resources\UserLoginResourse;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function updateProfile(ProfileUpdateRequest $request):object
    {
        $user = $request->user();
        $user->update($request->validated());
        return response()->json(new UserLoginResourse($user));
    }

    public function updatePassword(PasswordUpdateRequest $request):object
    {
        $user = $request->user();
        if (Hash::check($request['old_password'], $user->password)) {
            $user->update([
                'password' => $request['new_password'],
            ]);
            return response()->json(new UserLoginResourse($user));
        }
        return response()->json(['status' => 400, 'message' => "كلمة المرور غير صحيحة."], 400);
    }

    public function uploadImageAvatar(UploadImageRequest $request):object
    {
        $user = $request->user();
        $user->update([
            'image'=>$request->file('image')
        ]);
        return response()->json([
            "image" => $user->image
        ]);
    }
}
