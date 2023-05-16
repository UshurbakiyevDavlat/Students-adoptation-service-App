<?php

namespace App\Listeners;

use App\Events\MessagePushNotification;
use App\Services\FCMService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendPushNotificationListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param MessagePushNotification $event
     * @return void
     */
    public function handle(MessagePushNotification $event): void
    {
        // Logic for sending push notification
        // You can access the message and user like this:
        $message = $event->message;
        $user = $event->user;
        // Send push notification to user's device

        $token = $user->fcm_token;

        $notification = [
            'title' => $message->sender()->first()->name,
            'body' => $message->text,
            'icon' => '/neil_i_armstrong.jpeg',
        ];
        try {
            FCMService::send($token, $notification);
            Log::info('Message sent to user with id - ' . $user->id);
        } catch (\Exception $e) {
            Log::error('Error while sending message to user with id - ' . $user->id);
            Log::error($e->getMessage());
        }
    }
}
