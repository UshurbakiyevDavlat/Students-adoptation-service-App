<?php

namespace App\Http\Controllers\API\Map;

use App\Http\Controllers\Controller;
use App\Http\Requests\Map\UserMapLocationCreateRequest;
use App\Http\Requests\Map\UserMapLocationDeleteRequest;
use App\Http\Requests\Map\UserMapLocationUpdateRequest;
use App\Http\Requests\Map\UserMapPlaceCreateRequest;
use App\Http\Requests\Map\UserMapPlaceDeleteRequest;
use App\Http\Requests\Map\UserMapPlaceUpdateRequest;
use App\Http\Resources\Map\Map;
use App\Http\Resources\Map\MapCollection;
use App\Models\UserMap;
use App\Models\UserMapPlace;

class MapController extends Controller
{
    public function getUserMapLocation(UserMap $userMap): Map
    {
        return Map::make($userMap);
    }

    public function getUserPlacePoints(): MapCollection
    {
        return MapCollection::make(UserMapPlace::all()->paginate());
    }

    public function createUserLocation(UserMapLocationCreateRequest $request)
    {

    }

    public function createUserPlacePoint(UserMapPlaceCreateRequest $request)
    {

    }

    public function updateUserLocation(UserMapLocationUpdateRequest $request)
    {

    }

    public function updateUserPlacePoint(UserMapPlaceUpdateRequest $request)
    {

    }

    public function deleteUserLocation(UserMapLocationDeleteRequest $request)
    {

    }

    public function deleteUserPlacePoint(UserMapPlaceDeleteRequest $request)
    {

    }
}
