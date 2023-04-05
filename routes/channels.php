<?php

use App\Models\PersonalChat;
use Illuminate\Support\Facades\Broadcast;

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

    return $chat->firstUser()->first()->id === $user->id || $chat->secondUser()->first()->id === $user->id;
});
