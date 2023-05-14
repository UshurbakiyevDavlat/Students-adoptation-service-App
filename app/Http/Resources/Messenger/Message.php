<?php

namespace App\Http\Resources\Messenger;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
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
        return [
            'message_id' => $this->id,
            'my_message' => $this->sender_id === auth()->user()->getAuthIdentifier(),
            'message' => $this->text,
            'message_created_at' => $this->created_at,
            'message_updated_at' => $this->updated_at,
        ];
    }
}
