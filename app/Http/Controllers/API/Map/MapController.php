<?php

namespace App\Http\Controllers\API\Map;

use App\Http\Controllers\Controller;
use App\Http\Requests\Map\UserMapLocationCreateRequest;
use App\Http\Requests\Map\UserMapLocationUpdateRequest;
use App\Http\Requests\Map\UserMapPlaceCreateRequest;
use App\Http\Requests\Map\UserMapPlaceUpdateRequest;
use App\Http\Resources\Map\Map;
use App\Http\Resources\Map\MapCollection;
use App\Models\UserMap;
use App\Models\UserMapPlace;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    public function getUserMapLocation(): Map
    {
        $user = Auth::user();
        return Map::make($user?->mapsLocation()->paginate());
    }

    public function getUserPlacePoints($type = 'all', $lat = null, $long = null, $range = 1400): array
    {
        $user = Auth::user();
        $result = [];

        if ($user) {
            if ($type === 'all') {
                $notes = $user->mapsPlaces(new UserMapPlace(), $long, $lat, $range);
                $friends_ids = $user->mapsFriendsLocation()->pluck('user_maps.user_id')->toArray();
                $friends = $user->mapsPlaces(new UserMap(), $long, $lat, $range, $friends_ids);
                $result = [
                    'notes' => $notes,
                    'friends' => $friends
                ];
            } elseif ($type === 'notes') {
                $notes = $user->mapsPlaces(new UserMapPlace(), $long, $lat, $range);
                $result = [
                    'notes' => $notes
                ];
            } elseif ($type === 'friends') {
                $friends_ids = $user->mapsFriendsLocation()->pluck('user_id')->toArray();
                $friends = $user->mapsPlaces(new UserMap(), $long, $lat, $range, $friends_ids);
                $result = [
                    'friends' => $friends
                ];
            }
        }

        return $result; //TODO Возвращать, через коллекшн.
    }


    public function createUserLocation(UserMapLocationCreateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validated();
        $user?->mapsLocation()->create($data);

        return response()->json(['message' => __('map_location_success_creation'), 'location' => $user?->mapsLocation()->paginate()]);
    }

    public function createUserPlacePoint(UserMapPlaceCreateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validated();
        $user?->mapPlaces()->create($data);

        return response()->json(['message' => __('map_place_success_creation'), 'location' => $user?->mapPlaces()->paginate()]);
    }

    public function updateUserLocation(UserMapLocationUpdateRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $user?->mapsLocation()->update($data);

        return response()->json(['message' => __('map_location_success_update'), 'location' => $user?->mapsLocation()->paginate()]);
    }

    public function updateUserPlacePoint(UserMapPlace $point, UserMapPlaceUpdateRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $user?->mapPlaces()->where('id', $point->id)->update($data);

        return response()->json(['message' => __('map_place_success_update'), 'location' => $user?->mapPlaces()->paginate()]);
    }

    public function deleteUserLocation(): JsonResponse
    {
        $user = Auth::user();
        $user?->mapsLocation()->delete();

        return response()->json(['message' => __('map_location_success_delete')]);
    }

    public function deleteUserPlacePoint(UserMapPlace $point): JsonResponse
    {
        $user = Auth::user();
        $user?->mapPlaces()->where('id', $point->id)->delete();

        return response()->json(['message' => __('map_place_success_delete'), 'location' => $user?->mapPlaces()->paginate()]);
    }
}
