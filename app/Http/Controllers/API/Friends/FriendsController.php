<?php

namespace App\Http\Controllers\API\Friends;

use App\Enums\Friend\FriendStatus;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Friend\FriendCreateRequest;
use App\Http\Requests\Friend\FriendDeleteRequest;
use App\Http\Requests\Friend\FriendRequestCreateRequest;
use App\Http\Requests\Friend\FriendRequestUpdateRequest;
use App\Http\Resources\Friend\Friend;
use App\Http\Resources\Friend\FriendCollection;
use App\Http\Resources\Friend\Request\Friend as FriendRequest;
use App\Http\Resources\Friend\Request\FriendCollection as FriendRequestCollection;
use App\Models\User;
use App\Models\UserFriend;
use App\Models\UserFriendRequest;
use Illuminate\Http\JsonResponse;

class FriendsController extends Controller
{
    public function getFriendsRequest(UserFriendRequest $request): FriendRequest
    {
        return FriendRequest::make($request);
    }

    public function getFriend(UserFriend $friend): Friend
    {
        return Friend::make($friend);
    }

    public function getFriendsRequestsList(User $user): FriendRequestCollection
    {
        return FriendRequestCollection::make($user->friendsRequests()->get());
    }


    public function getFriendsList(User $user): FriendCollection
    {
        return FriendCollection::make($user->friends()->get());
    }

    public function createFriendRequest(FriendRequestCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $newFriendRequest = new UserFriendRequest($data);
        $newFriendRequest->save();

        return response()->json(['message' => __('friend_request_success_creation'), 'request' => $newFriendRequest]);

    }

    public function addFriend(FriendCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $userSide = UserFriend::create($data);

        UserFriendRequest::where('user_id', $data['user_id'])
            ->where('friend_id', $data['friend_id'])
            ->update(['status' => FriendStatus::ACTIVE]);

        (new Helpers())->swap($data['user_id'], $data['friend_id']);

        $friendSide = UserFriend::create($data);

        return response()->json(['message' => __('friend_success_creation'), 'userSide' => $userSide, 'friendSide' => $friendSide]);

    }

    public function deleteFriend(FriendDeleteRequest $request): JsonResponse
    {
        $data = $request->validated();

        //TODO Сделай плиз через релейшены, а то так не очень. Кода больше, и выглядит страшно, а производительность меньше.

        $userSide = UserFriend::where('friend_id', $data['friend_id'])
            ->where('user_id', $data['user_id'])
            ->first();

        $friendSide = UserFriend::where('friend_id', $data['user_id'])
            ->where('user_id', $data['friend_id'])
            ->first();

        $userSide->status = FriendStatus::DELETED;
        $friendSide->status = FriendStatus::DELETED;

        $userSide->save();
        $friendSide->save();

        $userSide->delete();
        $friendSide->delete();

        return response()->json(['message' => __('friend_success_deleted')]);
    }
}
