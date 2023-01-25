<?php

namespace App\Http\Controllers\API\Map;

use App\Http\Controllers\Controller;
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

    public function createUserLocation()
    {

    }

    public function createUserPlacePoint()
    {

    }

    public function updateUserLocation()
    {

    }

    public function updateUserPlacePoint()
    {

    }

    public function deleteUserLocation()
    {

    }

    public function deleteUserPlacePoint()
    {

    }
}
