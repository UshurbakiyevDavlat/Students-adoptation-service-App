<?php

namespace App\Http\Resources\Messenger;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $message = mb_convert_encoding($this->text, 'UTF-8');
        return [
            'message' => $message,
            'sender_id' => $this->sender_id,
            'message_created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
