<?php

namespace App\Http\Controllers\API\Hobby;

use App\Http\Controllers\Controller;
use App\Http\Resources\Hobby\HobbyCollection;
use App\Models\Hobby;

class HobbyController extends Controller
{
    public function index(): HobbyCollection
    {
        return HobbyCollection::make(Hobby::all());
    }
}
