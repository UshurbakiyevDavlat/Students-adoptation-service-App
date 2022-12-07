<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UserCreateRequest;
use App\Http\Requests\Profile\UserUpdateRequest;
use App\Http\Resources\Profile\User as UserResource;
use App\Http\Resources\Profile\UserCollection;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/",
     *     summary="GET list of users",
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */

    public function index(): UserCollection
    {
        return UserCollection::make(User::all());
    }
    /**
     * @OA\Get(
     *     path="/api/user/{id}",
     *     summary="GET concrete user",
     *     @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */

    public function show(User $user): UserResource
    {
        return UserResource::make($user);
    }

    /**
     * @OA\Post(
     *     path="/api/user",
     *     summary="Create user",
     *     @OA\Parameter(
     *         description="Parameter phone",
     *         in="path",
     *         name="phone",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="+77477782877", summary="A string with phone."),
     *     ),
     *     @OA\Parameter(
     *         description="Parameter password",
     *         in="path",
     *         name="password",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="somePassword", summary="A string with password."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function create(UserCreateRequest $userCreateRequest)
    {
        $userData = $userCreateRequest->validated();
        $userData['password'] = Hash::make($userData['password']);
        return User::create($userData);
    }

    //TODO: Сделать анотацию для свагера
    public function update(UserUpdateRequest $userUpdateRequest, User $user): bool
    {
        $userData = $userUpdateRequest->validated();

        return $user->update($userData);
    }

    /**
     * @OA\Delete(
     *     path="/api/user/{id}",
     *     summary="Delete user",
     *     security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *         description="Parameter user id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }
}
