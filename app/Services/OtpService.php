<?php

namespace App\Services;

use App\Interfaces\OtpInterface;
use App\Models\User;
use App\Models\UserEntryCode;
use App\Notifications\SendOtpCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Mobizon\Mobizon_ApiKey_Required;
use Mobizon\Mobizon_Curl_Required;
use Mobizon\Mobizon_Error;
use Mobizon\Mobizon_OpenSSL_Required;

class OtpService implements OtpInterface
{
    private $text;

    //TODO подумать как оптимизировать подобные методы, где стоит более 2 ретурнов
    public function sendOtp($user_phone): JsonResponse
    {
        $this->createCode($user_phone);

        //TODO нужна интеграция с отправкой по ватсапу

        //TODO чтобы работало, нужны деньги на счет,
        // так же разобраться, если вернуло это сообщение то, нужно понять почему не отдает как ошибку.
        try {
            (new SmsService($user_phone, $this->text))->sendSms();
        } catch (Mobizon_ApiKey_Required|Mobizon_Curl_Required|Mobizon_OpenSSL_Required|Mobizon_Error $exception) {
            return response()->json(['status' => 100, 'message' => 'see logs something went wrong in Mobizon integration'], 500);
        }

        return response()->json(['status' => 200, 'message' => 'Успешно отправлено']);
    }

    private function createCode($phone): void
    {
        $code = UserEntryCode::create([
            'code' => Str::random('6'),
            'phone' => $phone
        ])->code;

        $this->text = 'Ваш otp код:' . $code;
    }

}
