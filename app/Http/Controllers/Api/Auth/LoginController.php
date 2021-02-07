<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\UserLoginResourse;
use App\Models\User;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function login(LoginRequest $request):object
    {
        $credentials = $request->only('phone', 'password');
        $user = User::where(['phone' => $request['phone']])->first();
        if (!$user->phone_verified_at) {
            return response()->json(['status' => 400, 'message' => 'هذا الحساب غير مفعل'], 400);
        }
        if (auth('api')->attempt($credentials)) {
            $user->update([
                'device' => [
                    'id' => $request['id'],
                    'os' => $request['os'],
                ],
                'last_login_at' => Carbon::now(),
                'last_ip' => $request->ip(),
            ]);
            return response()->json(new UserLoginResourse($user));
        }
        return response()->json(['status' => 400, 'message' => "كلمة المرور غير صحيحة."], 400);
    }

    public function logout(): object
    {
        $user = auth('api')->user();
        $user->update([
            'device' => [
                'id' => null,
                'os' => null,
            ]
        ]);
        auth('api')->logout();
        return response()->json(['message' => "Logged out successfully."]);
    }
}
