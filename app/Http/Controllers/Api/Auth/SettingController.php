<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Auth\PasswordUpdateRequest;
use App\Http\Requests\Api\Auth\ProfileUpdateRequest;
use App\Http\Requests\Api\UploadImageRequest;
use App\Http\Resources\UserLoginResourse;
use Illuminate\Support\Facades\Hash;

class SettingController extends MasterController
{
    public function updateProfile(ProfileUpdateRequest $request): object
    {
        $user = $request->user();
        $user->update($request->validated());
        return $this->sendResponse(new UserLoginResourse($user));
    }

    public function updatePassword(PasswordUpdateRequest $request): object
    {
        $user = $request->user();
        if (Hash::check($request['old_password'], $user->password)) {
            $user->update([
                'password' => $request['new_password'],
            ]);
            return $this->sendResponse(new UserLoginResourse($user));
        }
        return $this->sendError('كلمة المرور غير صحيحة.');
    }

    public function uploadImageAvatar(UploadImageRequest $request):object
    {
        $user = $request->user();
        $user->update([
            'image'=>$request->file('image')
        ]);
        return $this->sendResponse([
            "image" => $user->image
        ]);
    }
}
