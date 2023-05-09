<?php

namespace App\Http\Resources\Map;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MapCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|Arrayable|\JsonSerializable
     */

    public static $wrap = null;

    public function toArray($request): AnonymousResourceCollection|array|\JsonSerializable|Arrayable
    {
        return Map::collection($this->collection);
    }
}
