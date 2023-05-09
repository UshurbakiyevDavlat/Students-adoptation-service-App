<?php

namespace App\Helpers;

class Helpers
{
    public function swap(&$a, &$b): void
    {
        $temp = $a;
        $a = $b;
        $b = $temp;
    }

    public static function getNearbyLocations($model,$latitude, $longitude, $range,$friends_ids = []): array
    {
        // Earth's radius in meters
        $earthRadius = 6371000;

        // Convert range from meters to radians
        $range /= $earthRadius;

        // Convert latitude and longitude to radians
        $latitude = deg2rad($latitude);
        $longitude = deg2rad($longitude);

        // Calculate minimum and maximum latitude and longitude
        $minLat = $latitude - $range;
        $maxLat = $latitude + $range;
        $minLng = $longitude - $range;
        $maxLng = $longitude + $range;

        // Query the database for locations within the specified range
        $locations = $model->where('latitude', '>=', rad2deg($minLat))
            ->where('latitude', '<=', rad2deg($maxLat))
            ->where('longitude', '>=', rad2deg($minLng))
            ->where('longitude', '<=', rad2deg($maxLng));

        if (!empty($friends_ids)) {
            $locations = $locations->whereIn('user_id', $friends_ids);
        }

        $locations = $locations->get();
        dd($locations, $longitude, $latitude, empty($friends_ids));

        // Calculate the distance of each location from the specified point
        foreach ($locations as &$location) {
            $lat = deg2rad($location->latitude);
            $lng = deg2rad($location->longitude);

            $distance = $earthRadius * acos(sin($latitude) * sin($lat) + cos($latitude) * cos($lat) * cos($lng - $longitude));

            $location->distance = $distance;
        }

        $locations = $locations->toArray();
        // Sort the locations by distance
        usort($locations, static function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        return $locations;
    }
}
