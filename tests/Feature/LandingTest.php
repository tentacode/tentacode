<?php

use function Pest\Laravel\get;

it('has no errors', function () {
    get('/')->assertStatus(200);
});

it('has a greeting section', function () {
    get('/')->assertSee('Bonjour!');
});

it('has a strength section', function () {
    get('/')->assertSee('I ship high quality projects');
});

it('has a recently blogged section', function () {
    get('/')->assertSee('Recently blogged');
});

it('has a portfolio section', function () {
    get('/')->assertSee('Portfolio');
});

it('has a contact section', function () {
    get('/')->assertSee('Get in touch');
});

it('has a footer', function () {
    get('/')->assertSee('If you read this');
});
