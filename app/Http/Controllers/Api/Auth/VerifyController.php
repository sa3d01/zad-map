<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Auth\ResendPhoneVerificationRequest;
use App\Http\Requests\Api\Auth\VerifyPhoneRequest;
use App\Http\Resources\ProviderLoginResourse;
use App\Http\Resources\UserLoginResourse;
use App\Models\PhoneVerificationCode;
use App\Models\User;
use App\Traits\UserPhoneVerificationTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VerifyController extends MasterController
{
    use UserPhoneVerificationTrait;

    public function resendPhoneVerification(ResendPhoneVerificationRequest $request): object
    {
        $user = User::where('phone', $request['phone'])->first();
        if ($user->phone_verified_at != null) {
            return $this->sendError('هذا الحساب مفعل.');
        }
        $unexpired_code_sent = PhoneVerificationCode::where('phone', $request['phone'])->where('expires_at', '>', Carbon::now())->latest()->first();
        if ($unexpired_code_sent) {
            return $this->sendError('تم ارسال كود التفعيل من قبل.');
        }
        $this->createPhoneVerificationCodeForUser($user);
        return $this->sendResponse([], 'تم إرسال كود التفعيل بنجاح .');
    }
    public function verifyPhone(VerifyPhoneRequest $request):object
    {
        $user = User::where('phone', $request['phone'])->first();
        if ($user->phone_verified_at != null) {
            return $this->sendError('هذا الحساب مفعل.');
        }
        $verificationCode = PhoneVerificationCode::where([
            'phone' => $request['phone'],
            'code' => $request['code'],
        ])->latest()->first();
        if (!$verificationCode) {
            return $this->sendError('كود التفعيل غير صحيح! حاول مرة أخرى.');
        }
        if (Carbon::now()->gt(Carbon::parse($verificationCode->expires_at))) {
            return $this->sendError('Code expired. ');
        }
        DB::transaction(function () use ($user, $verificationCode) {
            $now = Carbon::now();
            $verificationCode->update(['verified_at' => $now]);
            $user->update(['phone_verified_at' => $now]);
        });
        if ($user['type']!='USER'){
//            if ($user->approved==0){
//                $response = [
//                    'status' => 401,
//                    'message' => 'يرجى انتظار موافقة إدارة التطبيق ..',
//                ];
//                return response()->json($response, 401);
//            }
            return $this->sendResponse(new ProviderLoginResourse($user));
        }else{
            return $this->sendResponse(new UserLoginResourse($user));
        }
    }
}
