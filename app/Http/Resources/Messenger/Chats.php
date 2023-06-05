<?php

namespace App\Http\Resources\Messenger;

use App\Models\PersonalChat;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
        $chat_id = $this->pivot?->id ?: $this->id;
        $chat = PersonalChat::find($chat_id);
        $partner = $this->second_participant ? User::find($this->second_participant) : null;

        return [
            'chat_id' => $this->pivot?->id ?: $this->id,
            'ownerName' => $this->name ?: $partner?->name,
            'owner_avatar' => Storage::disk('public')->url($this->avatar) ?: Storage::disk('public')->url($partner?->avatar),
            'dialog_created_at' => $this->pivot?->created_at ?: $this->created_at,
            'dialog_last_message' => Message::make($chat?->messages()->latest()->first()),
        ];
    }
}
