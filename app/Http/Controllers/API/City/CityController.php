<?php

namespace App\Http\Controllers\API\City;

use App\Http\Controllers\Controller;
use App\Http\Resources\City\CityCollection;
use App\Models\City;

class CityController extends Controller
{
    public function index(): CityCollection
    {
        return CityCollection::make(City::all());
    }
}
