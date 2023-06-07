<?php

namespace App\Http\Controllers\API\Speciality;

use App\Http\Controllers\Controller;
use App\Http\Resources\Speciality\SpecialityCollection;
use App\Models\Speciality;

class SpecialityController extends Controller
{
    public function index(): SpecialityCollection
    {
        return SpecialityCollection::make(Speciality::all());
    }
}
