<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], static function () {
    Route::post('login', [AuthController::class,'login']);
    Route::get('logout',  [AuthController::class,'logout']);
    Route::get('refresh',  [AuthController::class,'refresh']);
    Route::get('me',  [AuthController::class,'me']);
});

Route::prefix('user')->group(
    static function () {
        Route::get('/{user}', [UserController::class, 'show']);
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'create']);
        Route::patch('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'delete'])->middleware('admin');
    });

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
