<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Friends\FriendsController;
use App\Http\Controllers\API\Map\MapController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\User\UserProfileController;
use App\Http\Controllers\Localization\LocaleController;
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
    Route::post('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
    Route::get('refresh', [AuthController::class, 'refresh'])->middleware('auth');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth');
});

//TODO сделать отдельный канал для логов
Route::get('/setLocale/{locale}', [LocaleController::class, 'setLocale']);

Route::prefix('send')->group(
    static function () {
        Route::get('/otp/{user_phone}', [AuthController::class, 'sendCode']);
    });

Route::prefix('user')->group(
    static function () {
        Route::get('/{user}', [UserController::class, 'show'])->name('user.show');
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/', [UserController::class, 'create'])->name('user.create');
        Route::post('/profile/{user}', [UserProfileController::class, 'fillProfile'])
            ->middleware('auth')
            ->name('profile.update');
        Route::post('/reset-password', [UserController::class, 'resetPassword'])
            ->middleware('guest')
            ->name('password.update');
        Route::delete('/{user}', [UserController::class, 'delete'])->middleware(['auth', 'admin'])->name('user.delete');
    });

Route::group(['prefix' => 'friends', 'middleware' => 'auth'], static function () {
    Route::prefix('request')->group(
        static function () {
            Route::get('/self/list', [FriendsController::class, 'getMyFriendsRequestList'])->name('my.friend.request.list');
            Route::get('{user}/list', [FriendsController::class, 'getFriendsRequestsList'])->name('friend.request.list');
            Route::post('/create', [FriendsController::class, 'createFriendRequest'])->name('friend.create_friends_request');
            Route::patch('/update', [FriendsController::class, 'updateFriendRequest'])->name('friend.update_friends_request');
        }
    );
    Route::get('{user}/list', [FriendsController::class, 'getFriendsList'])->name('friend.get_friends_list');
    Route::post('/create', [FriendsController::class, 'addFriend'])->name('friend.add_friend');
    Route::delete('/delete', [FriendsController::class, 'deleteFriend'])->name('friend.delete_friend');
});

Route::group(['prefix' => 'map', 'middleware' => 'auth'], static function () {
    Route::prefix('location')->group(
        static function () {
            Route::get('/', [MapController::class, 'getUserMapLocation'])->name('map.get_user_map_location');
            Route::post('/create', [MapController::class, 'createUserLocation'])->name('map.create_user_location');
            Route::patch('/update', [MapController::class, 'updateUserLocation'])->name('map.update_user_location');
            Route::delete('/delete', [MapController::class, 'deleteUserLocation'])->name('map.delete_user_location');
        }
    );

    Route::prefix('points')->group(
        static function () {
            Route::get('/', [MapController::class, 'getUserPlacePoints'])->name('map.get_map_points');
            Route::post('/create', [MapController::class, 'createUserPlacePoint'])->name('map.create_map_point');
            Route::patch('/update/{point}', [MapController::class, 'updateUserPlacePoint'])->name('map.update_map_point');
            Route::delete('/delete/{point}', [MapController::class, 'deleteUserPlacePoint'])->name('map.delete_map_point');
        }
    );
});
