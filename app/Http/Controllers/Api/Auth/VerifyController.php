<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ResendPhoneVerificationRequest;
use App\Http\Requests\Api\Auth\VerifyPhoneRequest;
use App\Http\Resources\UserLoginResourse;
use App\Models\PhoneVerificationCode;
use App\Models\User;
use App\Traits\UserPhoneVerificationTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VerifyController extends Controller
{
    use UserPhoneVerificationTrait;
    public function resendPhoneVerification(ResendPhoneVerificationRequest $request):object
    {
        $user = User::where('phone', $request['phone'])->first();
        if ($user->phone_verified_at != null) {
            return response()->json(['message' => 'هذا الحساب مفعل.'], 400);
        }
        $unexpired_code_sent=PhoneVerificationCode::where('phone',$request['phone'])->where('expires_at','>',Carbon::now())->latest()->first();
        if ($unexpired_code_sent){
            return response()->json(['message' => 'تم ارسال كود التفعيل من قبل.'], 400);
        }
        $this->createPhoneVerificationCodeForUser($user);
        return response()->json(['message' => 'تم إرسال كود التفعيل بنجاح .']);
    }
    public function verifyPhone(VerifyPhoneRequest $request):object
    {
        $user = User::where('phone', $request['phone'])->first();
        if ($user->phone_verified_at != null) {
            return response()->json(['message' => 'هذا الحساب مفعل.'], 400);
        }
        $verificationCode = PhoneVerificationCode::where([
            'phone' => $request['phone'],
            'code' => $request['code'],
        ])->latest()->first();
        if (!$verificationCode) {
            return response()->json(['message' => 'كود التفعيل غير صحيح! حاول مرة أخرى.'], 400);
        }
        if (Carbon::now()->gt(Carbon::parse($verificationCode->expires_at))) {
            return response()->json(['message' => 'Code expired. '], 400);
        }
        DB::transaction(function () use ($user, $verificationCode) {
            $now = Carbon::now();
            $verificationCode->update(['verified_at' => $now]);
            $user->update(['phone_verified_at' => $now]);
        });
        return response()->json(new UserLoginResourse($user));
    }
}
