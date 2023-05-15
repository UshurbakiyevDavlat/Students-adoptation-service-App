<?php

namespace App\Http\Controllers\API\Messenger;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messenger\CreateChatRequest;
use App\Http\Requests\Messenger\CreateMessageRequest;
use App\Http\Requests\Messenger\UpdateMessageRequest;
use App\Http\Resources\Messenger\ChatsCollection;
use App\Http\Resources\Messenger\MessageCollection;
use App\Models\Messages;
use App\Models\PersonalChat;
use Illuminate\Http\JsonResponse;

class MessengerController extends Controller
{

    public function getChats(): ChatsCollection
    {
        $user = auth()->user();
        return ChatsCollection::make($user->userChats()->get());
    }


    public function getChatMessages(PersonalChat $chat)
    {
        return MessageCollection::make($chat->messages()->get());
    }

    public function createChat(CreateChatRequest $request): JsonResponse
    {
        $first_user_id = auth()->user()->getAuthIdentifier();
        $second_user_id = $request->receiver;

        $data = [
            'first_participant' => $first_user_id,
            'second_participant' => $second_user_id
        ];

        PersonalChat::create($data);
        return response()->json(['message' => 'Chat created successfully'], 201);
    }

    public function createMessage(CreateMessageRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['sender_id'] = auth()->user()->getAuthIdentifier();
        Messages::create($data);
        return response()->json(['message' => 'Message created successfully'], 201);
    }

    public function updateMessage(Messages $message, UpdateMessageRequest $request): JsonResponse
    {

        if ($message->update([
            'text' => $request->text
        ])) {
            return response()->json(['message' => 'Updated successfully']);
        }
        return response()->json(['Error within updating'], 400);
    }

    public function deleteChat(PersonalChat $chat): JsonResponse
    {
        $chat->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function deleteMessage(Messages $message): JsonResponse
    {
        $message->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function searchChat($name): ChatsCollection
    {
        $chats = PersonalChat::whereHas('secondUser', static function ($query) use ($name) {
            $query->where('name', 'like', "%{$name}%")
                ->where('first_participant', auth()->user()->getAuthIdentifier());
        })->get();

        return ChatsCollection::make($chats);
    }
}
