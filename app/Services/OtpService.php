<?php

namespace App\Services;

use App\Interfaces\OtpInterface;
use App\Models\UserEntryCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OtpService implements OtpInterface
{
    private string $text;

    public function sendOtp($user_phone): JsonResponse
    {
        $this->createCode($user_phone);

        try {
            (new TwilioService())->sendMessage($this->text, '+' . $user_phone);
        } catch (\Exception $exception) {
            Log::error('Twilio', ['exception' => $exception->getTrace()]);
            return response()->json(['error', $exception->getMessage()], 400);
        }

        // TODO подключил мобизон, в качестве дополнительного сервиса смс рассылки.При использовании пополнить счет надо будет.
//        try {
//            (new MobizonService($user_phone, $this->text))->sendSms();
//        } catch (Mobizon_ApiKey_Required|Mobizon_Curl_Required|Mobizon_OpenSSL_Required|Mobizon_Error $exception) {
//            return response()->json(['status' => 100, 'message' => 'see logs something went wrong in Mobizon integration'], 500);
//        }

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
