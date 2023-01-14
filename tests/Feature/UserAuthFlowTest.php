<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class)->afterEach(fn() => Artisan::call('db:seed'));


function send_test_otp($phone = '123456789'): array
{
    return [
        'data' => get('/api/send/otp/' . $phone)->json(),
        'phone' => $phone
    ];
}

function test_register(): TestResponse
{
    $otp = send_test_otp();

    $data = [
        'phone' => $otp['phone'],
        'password' => 'testingPassword',
        'code' => $otp['data']['code'],
    ];

    return post('/api/user', $data);
}

function test_auth(): TestResponse
{
    $otp = send_test_otp();

    $data = [
        'phone' => $otp['phone'],
        'password' => 'testingPassword',
        'code' => $otp['data']['code'],
    ];

    test_register();

    return post('/api/auth/login', $data);
}


it('can send otp to user', function () {
    $phone = '123456789';
    $response = get('/api/send/otp/' . $phone)->assertStatus(200);
    $response->assertJsonStructure([
        'status',
        'message',
        'code'
    ]);
});

it('can register a user', function () {

    $register = test_register();

    $response = $register->assertStatus(201);
    $response->assertJsonStructure([
        'data' => [
            'phone',
            'updated_at',
            'created_at',
            'id'
        ]
    ]);
});

it('can login a user', function () {
    $login = test_auth();

    $response = $login->assertStatus(200);
    $response->assertJsonStructure(['access_token']);
});

it('forbid login a user', function () {
    $user = User::find(test_register()->json()['data']['id']);
    $otp = send_test_otp(123456);

    $data = [
        'phone' => $user->phone,
        'password' => 'testingPassword',
        'code' => $otp['data']['code'],
    ];

    $login = post('/api/auth/login', $data);

    $response = $login->assertStatus(403);
});

it('failed login a user', function () {
    $user = User::find(test_register()->json()['data']['id']);
    $otp = send_test_otp();

    $data = [
        'phone' => $user->phone,
        'password' => 'testingPassword1',
        'code' => $otp['data']['code'],
    ];

    $login = post('/api/auth/login', $data);
    $login->assertStatus(401);
});

it('can see a profile of user', fn() => get('/api/auth/me')->assertStatus(200));

it('can refresh a token', function () {
    $token = test_auth()->json()['access_token'];
    get('/api/auth/refresh', ['Bearer ' . $token])->assertStatus(200);
});

it('can logout a user', function () {
    $token = test_auth()->json()['access_token'];

    $response = get('/api/auth/logout', ['Authorization' => 'Bearer ' . $token])->assertStatus(200);
    $response->assertJson(['message' => 'Successfully logged out']);
});

it('can update user profile', function () {
    $data = [
        'email' => 'test@email.com',
        'name' => 'testName',
    ];

    $token = test_auth()->json()['access_token'];

    post('/api/user/profile/1', $data, ['Authorization' => 'Bearer ' . $token])->assertStatus(200);
});


it('can request password reset', function () {
    $user = User::find(test_register()->json()['data']['id']);
    $user->email = 'dushurbakiev@gmail.com';
    $user->save();

    $otp = send_test_otp($user['phone']);
    $data = [
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
        'code' => $otp['data']['code']
    ];

    $response = post('/api/user/reset-password', $data)->assertStatus(200);
    $response->assertJson(['text' => 'Your password has been reset!']);
});
