<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UserCreateRequest;
use App\Http\Requests\Profile\UserUpdateRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\Profile\User as UserResource;
use App\Http\Resources\Profile\UserCollection;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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

    //TODO: Сделать анотацию для свагера
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $token = Str::random(64);
        $data = $request->only('email', 'password', 'password_confirmation');
        $data['token'] = $token;

        DB::table('password_resets')->insert([
            'email' => $data['email'],
            'token' => bcrypt($data['token']),
        ]);

        $status = Password::reset(
            $data,
            static function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        $code = $status === Password::PASSWORD_RESET ? 200 : 100;

        return response()->json(['text' => __($status),'status' => $code]);
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
