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
        dd($data[0]['user']['avatar']);
        $data['avatar'] = Storage::url($data['avatar']);
        return $data;
    }
}
