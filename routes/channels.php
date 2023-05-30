<?php

use App\Models\PersonalChat;
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

Broadcast::channel('chat.{chatId}', static function ($user, $chatId) {
    $chat = PersonalChat::findOrFail($chatId);

    Log::channel('websockets')->debug('User authorized for channel: ' . $chatId, [
        'user_id' => $user->id,
        'chat_id' => $chatId,
    ]);

    return $chat->firstUser()->first()->id === $user->id || $chat->secondUser()->first()->id === $user->id;
});
