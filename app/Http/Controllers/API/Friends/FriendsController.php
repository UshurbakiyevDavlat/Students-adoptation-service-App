<?php

namespace App\Http\Controllers\API\Friends;

use App\Http\Controllers\Controller;
use App\Http\Resources\Friend\Friend;
use App\Http\Resources\Friend\FriendCollection;
use App\Http\Resources\Friend\Request\Friend as FriendRequest;
use App\Http\Resources\Friend\Request\FriendCollection as FriendRequestCollection;
use App\Models\UserFriend;
use App\Models\UserFriendRequest;

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

    public function addFriend()
    {

    }

    public function deleteFriend()
    {

    }
}
