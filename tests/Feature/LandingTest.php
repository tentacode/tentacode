<?php

use function Pest\Laravel\get;

it('has no errors', function () {
    get('/')->assertStatus(200);
});

it('has a title', function () {
    get('/')->assertSee('tentacode.dev');
});
