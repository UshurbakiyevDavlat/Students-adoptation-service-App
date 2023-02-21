<?php

namespace App\Http\Controllers\API\University;

use App\Http\Controllers\Controller;
use App\Http\Resources\University\UniversityCollection;
use App\Models\University;

class UniversityController extends Controller
{
    public function index(): UniversityCollection
    {
        return UniversityCollection::make(University::all());
    }
}
