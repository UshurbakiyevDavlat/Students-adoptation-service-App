<?php

namespace App\Http\Resources\Messenger;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class WebsocketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|Arrayable
    {
        $message = $this->text;
        $senderId = $this->sender_id;
        $createdAt = $this->created_at->toDateTimeString();

        Log::info('WebsocketResource', [
            'message' => $message,
            'sender_id' => $senderId,
            'message_created_at' => $createdAt,
        ]);
        return [
            'message' => $message,
            'sender_id' => $senderId,
            'message_created_at' => $createdAt,
        ];
    }
}
