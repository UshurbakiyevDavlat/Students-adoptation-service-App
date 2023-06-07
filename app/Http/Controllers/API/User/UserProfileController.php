<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserFillProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    public function fillProfile(UserFillProfileRequest $request): JsonResponse
    {
        $user = auth()->user();
        $data = $request->validated();
        $user->update($data);

        if (isset($data['hobbies_ids'])) {
            $hobbies_ids = $data['hobbies_ids'];
            unset($data['hobbies_ids']);
            $user->hobbies()->sync($hobbies_ids);
        }

        return response()->json(['message' => 'Профиль успешно обновлен.']);
    }
}
