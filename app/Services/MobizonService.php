<?php

namespace App\Services;

use App\Interfaces\SmsInterface;
use Illuminate\Support\Facades\Log;
use Mobizon\Mobizon_ApiKey_Required;
use Mobizon\Mobizon_Curl_Required;
use Mobizon\Mobizon_Error;
use Mobizon\Mobizon_Http_Error;
use Mobizon\Mobizon_OpenSSL_Required;
use Mobizon\Mobizon_Param_Required;
use Mobizon\MobizonApi;

class MobizonService implements SmsInterface
{
    private MobizonApi $api;
    private string $recipient;
    private string $text;

    /**
     * @throws Mobizon_Error
     * @throws Mobizon_ApiKey_Required
     * @throws Mobizon_OpenSSL_Required
     * @throws Mobizon_Curl_Required
     */
    public function __construct($recipient, $text)
    {
        $this->api = new MobizonApi(env('MOBIZON_API_KEY'), 'api.mobizon.kz');
        $this->recipient = $recipient;
        $this->text = $text;
    }

    /**
     * @throws Mobizon_Http_Error
     * @throws Mobizon_Param_Required
     */
    public function sendSms(): void
    {
        $send = $this->api->call('message', 'sendSMSMessage', [
            'recipient' => $this->recipient,
            'text' => $this->text,
        ]);

        try {
            $this->checkStatus($send);
        } catch (Mobizon_Http_Error|Mobizon_Param_Required $exception) {
            Log::error('Mobizon', ['message' => $exception->getMessage()]);
        }
    }

    /**
     * @throws Mobizon_Http_Error
     * @throws Mobizon_Param_Required
     */
    private function checkStatus($sentSms): void
    {
        if ($sentSms) {
            $messageId = $this->api->getData('messageId');
            Log::info('sms', ['message' => 'Message created with ID:' . $messageId . PHP_EOL]);
            if ($messageId) {
                Log::info('sms', ['message' => 'Get info...']);
                $messageStatuses = $this->api->call(
                    'message',
                    'getSMSStatus',
                    [
                        'ids' => [$messageId]
                    ],
                    [],
                    true
                );
            }
            if ($this->api->hasData()) {
                foreach ($this->api->getData() as $messageInfo) {
                    Log::info('sms', ['Message # ' . $messageInfo->id . " status:\t" . $messageInfo->status . PHP_EOL]);
                }
            }
        } else {
            Log::error('sms', ['An error occurred while sending message: [' . $this->api->getCode() . '] ' . $this->api->getMessage() . 'See details below:' . PHP_EOL]);
            Log::error('smsDebug', [$this->api->getCode(), $this->api->getData(), $this->api->getMessage()]);
        }
    }
}
