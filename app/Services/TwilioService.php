<?php

namespace App\Services;

use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioService
{
    /**
     * @throws ConfigurationException
     * @throws TwilioException
     */
    public function sendMessage($message, $recipients): void
    {
        $account_sid = env('TWILIO_SID');
        $auth_token = env('TWILIO_AUTH_TOKEN');
        $twilio_number = env('TWILIO_NUMBER');

        $client = new Client($account_sid, $auth_token);

        $client->messages->create($recipients,
            [
                'from' => $twilio_number,
                'body' => $message
            ]);
    }
}
