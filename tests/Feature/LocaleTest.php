<?php

use function Pest\Laravel\get;

it('set locale', fn() => get('/api/setLocale/ru')->assertStatus(200));
it('can not set locale', fn() => get('/api/setLocale/uz')->assertStatus(400));
it('can see admin panel for languages', fn() => get('/languages')->assertStatus(200));
