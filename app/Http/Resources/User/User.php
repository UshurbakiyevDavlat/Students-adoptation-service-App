<?php

namespace App\Http\Resources\User;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|Arrayable
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'avatar' => Storage::url($this->avatar),
            'hobbies' => $this->hobbies ,
            'city' => $this->city ,
            'university' => $this->university ,
            'speciality' => $this->speciality ,
            'fcm_token' => $this->fcm_token,
            'birth_date' => $this->birth_date,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'access_token' => $this->token?->access_token ?? null,
        ];

        return $data;

    }
}
