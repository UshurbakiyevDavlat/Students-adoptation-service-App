<?php

namespace App\Observers;

use App\Events\MessagePushNotification;
use App\Models\Messages;

class MessageObserver
{
    public function created(Messages $message): void
    {
        $user = auth()->user();
        event(new MessagePushNotification($message, $user));
    }
}
