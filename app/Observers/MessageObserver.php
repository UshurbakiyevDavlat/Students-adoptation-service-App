<?php

namespace App\Observers;

use App\Events\MessagePushNotification;
use App\Models\Messages;
use Illuminate\Support\Facades\Log;

class MessageObserver
{
    public function created(Messages $message): void
    {
        $user = auth()->user();
        Log::info('Message' . $message->text);
        event(new MessagePushNotification($message, $user));
    }
}
