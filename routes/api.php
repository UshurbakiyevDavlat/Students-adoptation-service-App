<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Profile\UserController;
use App\Http\Controllers\Profile\UserProfileController;
//use App\Http\Controllers\Services\ElasticSearchController;
use App\Http\Controllers\Services\LocaleController;
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
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

//TODO сделать отдельный канал для логов
Route::get('/setLocale/{locale}', [LocaleController::class, 'setLocale']);

Route::prefix('send')->group(
    static function () {
        Route::get('/otp/{user_phone}', [AuthController::class, 'sendCode']);
    });

//Route::prefix('elastic')->group(
//    static function () {
//        Route::get('/import', [ElasticSearchController::class, 'import']);
//        Route::get('/search/{query}', [ElasticSearchController::class, 'search']);
//    }
//);

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
