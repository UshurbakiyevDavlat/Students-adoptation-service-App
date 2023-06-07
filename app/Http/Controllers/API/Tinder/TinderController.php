<?php

namespace App\Http\Controllers\API\Tinder;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TinderController extends Controller
{
    public function matching(): JsonResponse
    {
        $user = auth()->user();

        // Get the current user's hobbies IDs
        $hobbyIds = $user->hobbies->pluck('id');

        $matchingUsers = User::where('id', '<>', $user->id)
            ->where(function ($query) use ($user) {
                $query->where('city_id', $user->city_id)
                    ->orWhere('university_id', $user->university_id)
                    ->orWhere('speciality_id', $user->speciality_id);
            })
            ->whereDoesntHave('matches', function ($query) {
                $query->whereNotNull('is_match');
            })
            ->whereHas('hobbies', function ($query) use ($hobbyIds) {
                $query->whereIn('hobby_id', $hobbyIds);
            })
            ->withCount(['hobbies' => function ($query) use ($hobbyIds) {
                $query->whereIn('hobby_id', $hobbyIds);
            }])
            ->orderByDesc('hobbies_count')
            ->get();


        if ($matchingUsers->isEmpty()) {
            return response()->json([
                'message' => 'No matching users found',
            ]);
        }

        $matchingUser = $matchingUsers->first();

        if ($user->matches()->where('partner_id', $matchingUser->id)->first()) {
            return response()->json(['user' => \App\Http\Resources\User\User::make($matchingUser)]);
        }

        DB::beginTransaction();
        try {
            $user->matches()->create([
                'partner_id' => $matchingUser->id,
            ]);

            $user->matchedBy()->create([
                'user_id' => $matchingUser->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error while matching',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json(['User' => \App\Http\Resources\User\User::make($matchingUser)]);

    }


    public function setLikeOrDislike(User $partner, bool $status): JsonResponse
    {
        $liked = $status ? 1 : 2;
        $user = auth()->user();

        $matches = $user->matches()->get();
        $partner_matches = $user->matchedBy()->get();

        $matches->each(function ($match) use ($partner, $liked, $user) {
            if ($match->partner_id == $partner->id) {
                $match->is_match = $liked;
                $match->save();

                $user->friendsRequests()->create([
                    'user_id' => $user->id,
                    'friend_id' => $partner->id,
                ]);
            }
        });

        $partner_matches->each(function ($match) use ($partner, $liked) {
            if ($match->user_id == $partner->id) {
                $match->is_match = $liked;
                $match->save();
            }
        });

        return response()->json(['message' => $status ? 'User was liked!' : 'User was disliked']);
    }
}
