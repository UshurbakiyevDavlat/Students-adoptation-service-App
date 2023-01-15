<?php

use function Pest\Laravel\get;

it('can not connect to websockets dashboard', function () {
    get('/laravel-websockets')->assertStatus(403);
});
