<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserEntryCode;
use App\Notifications\SendOtpCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Mobizon\Mobizon_ApiKey_Required;
use Mobizon\Mobizon_Curl_Required;
use Mobizon\Mobizon_Error;
use Mobizon\Mobizon_OpenSSL_Required;

class OtpService
{
    private $text;

    public function sendOtp($user_phone): JsonResponse
    {
        $user = User::select(['id'])->where('phone', $user_phone)->first();
        if (!$user) {
            return response()->json(['message' => 'User with this phone is not existing', 'status' => 100]);
        }

        $userId = $user->id;

        $this->createCode($userId);

        $user->notify(new SendOtpCode($this->text));

        //TODO чтобы работало, нужны деньги на счет
        try {
            (new SmsService($user_phone, $this->text))->sendSms();
        } catch (Mobizon_ApiKey_Required|Mobizon_Curl_Required|Mobizon_OpenSSL_Required|Mobizon_Error $exception) {
            return response()->json(['status' => 100, 'message' => 'see logs something went wrong in Mobizon integration'], 500);
        }

        return response()->json(['status' => 200, 'message' => 'Успешно отправлено']);
    }

    private function createCode($userId): void
    {
        $code = UserEntryCode::create([
            'code' => Str::random('6'),
            'user_id' => $userId
        ])->code;

        $this->text = 'Ваш otp код:' . $code;
    }

}
