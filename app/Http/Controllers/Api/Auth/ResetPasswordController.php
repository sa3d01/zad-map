<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
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

class ResetPasswordController extends MasterController
{
    use UserPasswordResetTrait;

    public function resend(ResendForgotPasswordRequest $request): object
    {
        $user = User::where('phone', $request['phone'])->first();
        $this->createPasswordResetCodeForUser($user);
        return $this->sendResponse([], 'Password reset token has been sent to your phone.');
    }

    public function forgotPassword(ForgotPasswordRequest $request):object
    {
        $user = User::where('phone', $request['phone'])->first();
        $this->createPasswordResetCodeForUser($user);
        return $this->sendResponse([], 'Password reset token has been sent to your phone.');
    }

    public function checkCode(CheckTokenRequest $request):object
    {
        $passwordResetObject = PasswordReset::where([
            'phone' => $request['phone'],
            'token' => $request['code'],
        ])->latest()->first();
        if (!$passwordResetObject) {
            return $this->sendError('Wrong token! Please try again.');
        }
        if (Carbon::now()->gt(Carbon::parse($passwordResetObject->expires_at))) {
            return $this->sendError('Token expired. Please request a new one.');
        }
        return $this->sendResponse([], 'Phone and token match successfully.');
    }

    public function setNewPassword(SetPasswordRequest $request):object
    {
        $passwordResetObject = PasswordReset::where([
            'phone' => $request['phone'],
            'token' => $request['code'],
        ])->latest()->first();
        if (!$passwordResetObject) {
            return $this->sendError('Wrong token! Please try again.');
        }
        if (Carbon::now()->gt(Carbon::parse($passwordResetObject->expires_at))) {
            return $this->sendError('Token expired. Please request a new one.');
        }
        $user = User::where('phone', $request['phone'])->first();
        DB::transaction(function () use ($user, $passwordResetObject, $request) {
            $passwordResetObject->update(['verified' => Carbon::now()]);
            $user->update(['password' => $request['password']]);
        });
        return $this->sendResponse(new UserLoginResourse($user));
    }

}
