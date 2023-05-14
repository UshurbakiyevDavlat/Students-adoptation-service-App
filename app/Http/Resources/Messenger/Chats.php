<?php

namespace App\Http\Resources\Messenger;

use App\Models\PersonalChat;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class Chats extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|Arrayable
    {
        $chat = PersonalChat::find($this->pivot->id);

        return [
            'chat_id' => $this->pivot->id,
            'ownerName' => $this->name,
            'owner_avatar' => $this->avatar,//TODO Добавить аватар юзераs
            'dialog_created_at' => $this->pivot->created_at,
            'dialog_last_message' => Message::make($chat->messages()->latest()->first()),
        ];
    }
}
