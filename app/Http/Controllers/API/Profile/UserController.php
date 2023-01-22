<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Profile\UserCreateRequest;
use App\Http\Resources\Profile\User as UserResource;
use App\Http\Resources\Profile\UserCollection;
use App\Models\User;
use App\Models\UserEntryCode;
use App\Notifications\ResetPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
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
        return UserCollection::make((new User())->setFilters(['name', 'phone'])->getFiltered());
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
    public function create(UserCreateRequest $request): JsonResponse|UserResource
    {
        $userData = $request->validated();
        $userData['password'] = Hash::make($userData['password']);
        $entryCode = UserEntryCode::where('code', $userData['code'])
            ->where('used', 0)
            ->first();

        $errors = null;

        if (!$entryCode) {
            return response()
                ->json(['status' => 403, 'message' => 'This code does not exist or has been used.'], 403);
        }

        $user = User::create($userData);
        $entryCode->used = 1;
        $entryCode->save();
        DB::commit();

        return $errors ?: UserResource::make($user);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $token = Str::random(64);
        $data = $request->only('email', 'password', 'password_confirmation');

        //TODO Вот такие конструкции нужно выносить в отдельный сервис или метод,
        // лучше сервис, позже надо найти вынести и заменить.
        $entryCode = UserEntryCode::where('code', $request->code)->first();
        $user = User::where('email', $data['email'])->first();

        if ($entryCode && ($user->id !== $entryCode->user->id)) {
            return response()->json(['status' => 403, 'message' => 'This user has not such code'], 403);
        }

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
                $user->notify((new ResetPassword($password)));

                event(new PasswordReset($user));
            }
        );

        $code = $status === Password::PASSWORD_RESET ? 200 : 100;

        return response()->json(['text' => __($status), 'status' => $code]);
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }
}
