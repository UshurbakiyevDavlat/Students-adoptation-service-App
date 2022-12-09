<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    public function setLocale($locale): JsonResponse
    {
        if (!in_array($locale, ['ru', 'en', 'kz'])) {
            return response()->json(['message' => 'Unset ' . App::getLocale() . ' locale', 'status' => 100, 'reason' => 'Illegal locale within app']);
        }
        App::setLocale($locale);
        return response()->json(['message' => 'successfully set ' . App::getLocale() . ' locale', 'status' => 200]);
    }
}
