<?php

use function Pest\Laravel\get;

it('has home', function () {
    get('/')->assertStatus(200);
});
