<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFillProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    public function fillProfile(User $user, UserFillProfileRequest $request): JsonResponse
    {
        $data = $request->validated();
        try {
            $user->update($data);
            if (isset($data['hobbies_ids'])) {
                $hobbies_ids = $data['hobbies_ids'];
                unset($data['hobbies_ids']);
                $user->hobbies()->sync($hobbies_ids);
            }
        } catch (\Exception $exception) {
            Log::error('debug', ['trace' => $exception->getTraceAsString()]);
            return response()->json(['message' => 'Произошла ошибка.Профиль не обновлен.'], 400);
        }
        return response()->json(['message' => 'Профиль успешно обновлен.']);
    }
}
