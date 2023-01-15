<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UserFillProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    public function fillProfile(User $user, UserFillProfileRequest $request): JsonResponse
    {
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
