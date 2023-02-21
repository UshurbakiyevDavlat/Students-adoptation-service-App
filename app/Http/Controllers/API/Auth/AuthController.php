<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Models\UserEntryCode;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function sendCode($user_phone): JsonResponse
    {
        return (new OtpService())->sendOtp($user_phone);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    //TODO подумать как оптимизировать подобные методы, где стоит более 2 ретурнов
    public function login(UserLoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['phone', 'password']);

        //TODO Вот такие конструкции нужно выносить в отдельный сервис или метод,
        // лучше сервис, позже надо найти вынести и заменить.
        $entryCode = UserEntryCode::where('code', $request->code)
            ->where('phone', $credentials['phone'])
            ->where('used', 0)
            ->first();

        if (!$entryCode || !$token = auth()->attempt($credentials)) {
            if ($entryCode) {
                $response = response()->json(['error' => 'Unauthorized'], 401);
            } else {
                $response = response()
                    ->json(['status' => 403, 'message' => 'This user has not such code. Or code was already used'], 403);
            }
            return $response;
        }

        $entryCode->used = 1;
        $entryCode->save();

        return response()->json(['id' => auth()->user()->id,'token_data' => $this->respondWithToken($token)]);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
