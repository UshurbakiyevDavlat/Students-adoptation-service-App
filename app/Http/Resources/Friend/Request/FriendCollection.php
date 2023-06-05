<?php

namespace App\Http\Resources\Friend\Request;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class FriendCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|Arrayable
    {
        $data = parent::toArray($request);
        foreach ($data as $key => $value) {
            if (isset($value['user']['avatar'])) {
                $data[$key]['user']['avatar'] = Storage::disk('public')->url($value['user']['avatar']);
            }
        }
        return $data;
    }
}
