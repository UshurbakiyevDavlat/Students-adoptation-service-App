<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface OtpInterface
{
    public function sendOtp($user_phone): JsonResponse;
}
