<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UserRegisterationRequest;
use App\Models\User;
use App\Traits\UserPhoneVerificationTrait;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    use UserPhoneVerificationTrait;

    public function register(UserRegisterationRequest $request): object
    {
        $data = $request->validated();
        $data['last_ip'] = $request->ip();
        $user = User::create($data);
        //todo : request user type
        $role = Role::findOrCreate("USER");
        $user->assignRole($role);
        $this->createPhoneVerificationCodeForUser($user);
        return response()->json([
            "phone" => $request["phone"]
        ]);

    }
}
