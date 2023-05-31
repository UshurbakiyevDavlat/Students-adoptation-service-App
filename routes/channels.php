<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {

    Log::channel('websockets')->debug('User authorized for channel: ' . $chatId, [
        'user_id' => $user->id,
        'chat_id' => $chatId,
    ]);

    return [
        'user_id' => $user->id,
//        'message' => $this->message,
        'time' => now()
    ];
});
