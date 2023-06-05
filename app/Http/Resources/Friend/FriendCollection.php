<?php

namespace App\Http\Resources\Friend;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class FriendCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data =  parent::toArray($request);
        foreach ($data as $key => $value) {
            $data[$key]['user']['avatar'] = Storage::disk('public')->url($value['user']['avatar']);
        }
        return $data;
    }
}
