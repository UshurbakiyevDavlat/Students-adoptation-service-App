<?php

namespace App\Http\Controllers\API\Friends;

use App\Enums\Friend\FriendStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Friend\FriendCreateRequest;
use App\Http\Requests\Friend\FriendDeleteRequest;
use App\Http\Requests\Friend\FriendRequestCreateRequest;
use App\Http\Requests\Friend\FriendRequestUpdateRequest;
use App\Http\Resources\Friend\Friend;
use App\Http\Resources\Friend\FriendCollection;
use App\Http\Resources\Friend\Request\Friend as FriendRequest;
use App\Http\Resources\Friend\Request\FriendCollection as FriendRequestCollection;
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

    public function getFriendsRequests(): FriendRequestCollection
    {
        return FriendRequestCollection::make(UserFriendRequest::all()->paginate());
    }

    public function getFriendsList(): FriendCollection
    {
        return FriendCollection::make(UserFriend::all()->paginate());
    }

    public function createFriendRequest(FriendRequestCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $newFriendRequest = new UserFriendRequest($data);
        $newFriendRequest->save();

        return response()->json(['message' => __('friend_request_success_creation'), 'request' => $newFriendRequest]);

    }

    public function updateFriendRequest(FriendRequestUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userFriendRequest = UserFriendRequest::where('user_id', $data['user_id'])
            ->where('friend_id', $data['friend_id'])
            ->whereNotNull('deleted_at')
            ->first();

        $userFriendRequest->status = $data['status'];
        $userFriendRequest->save();

        if ($data['status'] === FriendStatus::DELETED) {
            $userFriendRequest->delete();
        }

        return response()->json(['message' => __('friend_request_success_update'), 'request' => $userFriendRequest]);
    }

    public function addFriend(FriendCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $newFriend = UserFriend::create($data);

        return response()->json(['message' => __('friend_success_creation'), 'request' => $newFriend]);

    }

    public function deleteFriend(FriendDeleteRequest $request): JsonResponse
    {
        $data = $request->validated();

        $friend = UserFriend::where('friend_id', $data['friend_id'])
            ->where('user_id', $data['user_id'])
            ->first();


        $friend->status = FriendStatus::DELETED;
        $friend->save();

        $friend->delete();

        return response()->json(['message' => __('friend_success_deleted')]);
    }
}
