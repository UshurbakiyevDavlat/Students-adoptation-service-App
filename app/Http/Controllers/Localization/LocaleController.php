<?php

namespace App\Http\Controllers\Localization;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    public function setLocale($locale): JsonResponse
    {
        if (!in_array($locale, ['ru', 'en', 'kz'])) {
            return response()->json(['message' => 'Unset ' . App::getLocale() . ' locale', 'status' => 400, 'reason' => 'Illegal locale within app'],400);
        }
        App::setLocale($locale);
        return response()->json(['message' => 'successfully set ' . App::getLocale() . ' locale', 'status' => 200]);
    }
}
