<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UserCreateRequest;
use App\Http\Requests\Profile\UserUpdateRequest;
use App\Http\Resources\Profile\User as UserResource;
use App\Http\Resources\Profile\UserCollection;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
        $userData['password'] = Hash::make($userData['password']);
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
