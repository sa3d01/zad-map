<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\PasswordReset\CheckTokenRequest;
use App\Http\Requests\Api\Auth\PasswordReset\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\PasswordReset\ResendForgotPasswordRequest;
use App\Http\Requests\Api\Auth\PasswordReset\SetPasswordRequest;
use App\Http\Resources\UserLoginResourse;
use App\Models\PasswordReset;
use App\Models\User;
use App\Traits\UserPasswordResetTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    use UserPasswordResetTrait;

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('phone', $request['phone'])->first();
        $this->createPasswordResetCodeForUser($user);
        return response()->json(['message' => 'Password reset token has been sent to your phone.'], 200);
    }

    public function resend(ResendForgotPasswordRequest $request)
    {
        $user = User::where('phone', $request['phone'])->first();
        $this->createPasswordResetCodeForUser($user);
        return response()->json(['message' => 'Password reset token has been sent to your phone.'], 200);
    }

    public function checkCode(CheckTokenRequest $request)
    {
        $passwordResetObject = PasswordReset::where([
            'phone' => $request['phone'],
            'token' => $request['code'],
        ])->latest()->first();
        if (!$passwordResetObject) {
            return response()->json(['field' => 'token', 'message' => 'Wrong token! Please try again.'], 422);
        }
        if (Carbon::now()->gt(Carbon::parse($passwordResetObject->expires_at))) {
            return response()->json(['field' => 'token', 'message' => 'Token expired. Please request a new one.'], 422);
        }
        return response()->json(['message' => 'Phone and token match successfully.'], 200);
    }

    public function setNewPassword(SetPasswordRequest $request)
    {
        $passwordResetObject = PasswordReset::where([
            'phone' => $request['phone'],
            'token' => $request['code'],
        ])->latest()->first();
        if (!$passwordResetObject) {
            return response()->json(['field' => 'token', 'message' => 'Wrong token! Please try again.'], 422);
        }
        if (Carbon::now()->gt(Carbon::parse($passwordResetObject->expires_at))) {
            return response()->json(['field' => 'token', 'message' => 'Token expired. Please request a new one.'], 422);
        }

        $user = User::where('phone', $request['phone'])->first();
        DB::transaction(function () use ($user, $passwordResetObject, $request) {
            $passwordResetObject->update(['verified' => Carbon::now()]);
            $user->update(['password' => $request['password']]);
        });
        return response()->json(new UserLoginResourse($user), 200);
    }

}
