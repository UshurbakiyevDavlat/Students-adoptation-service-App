<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use App\Models\User;

class UserController extends Controller
{
    public function index(): UserCollection
    {
        return UserCollection::make(User::all());
    }

    public function show(User $user): UserResource
    {
        return UserResource::make($user);
    }

    public function create(UserCreateRequest $userCreateRequest)
    {
        $userData = $userCreateRequest->validated();
        return User::create($userData);
    }

    public function update(UserUpdateRequest $userUpdateRequest, User $user): bool
    {
        $userData = $userUpdateRequest->validated();

        return $user->update($userData);
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }
}
