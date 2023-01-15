<?php

use App\Models\Hobby;
use App\Models\User;
use App\Models\UserEntryCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);
beforeEach(fn() => Artisan::call('migrate:fresh --seed'));


function send_test_otp($phone = '123456789'): array
{
    return [
        'data' => get('/api/send/otp/' . $phone)->json(),
        'phone' => $phone
    ];
}

function test_register($used = 0, $can = 1): TestResponse
{
    $otp = send_test_otp();

    if ($used) {
        $code = UserEntryCode::where(['code' => $otp['data']['code']])->first();
        $code->used = 1;
        $code->save();
    }

    $data = [
        'phone' => $otp['phone'],
        'password' => 'testingPassword',
        'code' => $otp['data']['code'],
    ];

    if (!$can) {
        unset($data['phone']);
    }

    return post('/api/user', $data);
}

function test_auth($admin = 0, $can = 1): TestResponse
{
    $otp = send_test_otp();

    $data = [
        'phone' => $otp['phone'],
        'password' => 'testingPassword',
        'code' => $otp['data']['code'],
    ];

    if (!$can) {
        unset($data['phone']);
    }

    test_register();

    if ($admin) {
        $user = User::where(['phone' => $otp['phone']])->first();
        $user->assignRole('admin');
    }
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

it('forbid not register a user', function () {
    $register = test_register(1);
    $register->assertStatus(403);
});

it('can not register a user', function () {
    $register = test_register(0, 0);
    $register->assertStatus(422);
});

it('can login a user', function () {
    $login = test_auth();

    $response = $login->assertStatus(200);
    $response->assertJsonStructure(['access_token']);
});

it('can not login a user', function () {
    $login = test_auth(0, 0);
    $login->assertStatus(422);
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
    $login->assertStatus(403);
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

it('can see a profile of user', function () {
    $token = test_auth()->json()['access_token'];
    get('/api/auth/me', ['Authorization' => 'Bearer ' . $token])->assertStatus(200);
});

it('can see a list of users data', fn() => get('/api/user')->assertStatus(200));
it('can see a filtered list of users data', fn() => get('/api/user?filter[name]=Davlat&filter[phone]=7747')
    ->assertStatus(200));
it('can see a concrete user data', fn() => get('/api/user/1')->assertStatus(200));

it('can not delete user, if not authorized', fn() => delete('/api/user/1')->assertStatus(500));
it('can not delete user, if not admin', function () {
    $token = test_auth()->json()['access_token'];
    delete('/api/user/1', ['Authorization', 'Bearer ' . $token])->assertStatus(403);
});

it('can delete user, if admin', function () {
    $token = test_auth(1)->json()['access_token'];
    delete('/api/user/1', ['Authorization', 'Bearer ' . $token])->assertStatus(200);
});

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
        'birth_date' => '2000-03-19',
        'city_id' => 1,
        'university_id' => 1,
        'speciality_id' => 1,
        'hobbies_ids' => [1, 2, 3]
    ];

    $token = test_auth()->json()['access_token'];

    post('/api/user/profile/1', $data, ['Authorization' => 'Bearer ' . $token])->assertStatus(200);

    $hobbies = Hobby::whereIn('id', [1, 2, 3])->first();
    expect($hobbies->users()->get())->toBeObject();
});

it('can not update user profile', function () {
    $data = [
        'email' => 'test@email.com',
        'name' => 'testName',
        'birth_date' => '2000-03-19',
        'city_id' => 99,
        'university_id' => 1,
        'hobbies_ids' => [1, 2, 3]
    ];

    $token = test_auth()->json()['access_token'];

    post('/api/user/profile/1', $data, ['Authorization' => 'Bearer ' . $token])->assertStatus(422);

    $hobbies = Hobby::whereIn('id', [1, 2, 3])->first();
    expect($hobbies->users()->get())->toBeObject();
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

it('forbid to request password reset', function () {
    $user = User::find(test_register()->json()['data']['id']);
    $user->email = 'dushurbakiev@gmail.com';
    $user->save();

    $otp = send_test_otp($user['phone']);

    $another_user = User::find(1);
    $another_otp = send_test_otp($another_user->phone);

    $data = [
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
        'code' => $another_otp['data']['code']
    ];

    post('/api/user/reset-password', $data)->assertStatus(403);
});

it('can not to request password reset', function () {
    $user = User::find(test_register()->json()['data']['id']);
    $user->email = 'dushurbakiev@gmail.com';
    $user->save();

    $otp = send_test_otp($user['phone']);

    $another_user = User::find(1);
    $another_otp = send_test_otp($another_user->phone);

    $data = [
        'email' => $user->email,
        'password' => 'password',
        'code' => $another_otp['data']['code']
    ];

    post('/api/user/reset-password', $data)->assertStatus(422);
});
