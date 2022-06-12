<?php

use function Pest\Laravel\get;

it('has no errors', function () {
    get('/')->assertStatus(200);
});

it('has a greeting section', function () {
    get('/')->assertSee('Bonjour !');
});

it('has a strength section', function () {
    get('/')->assertSee('Qualité');
});

it('has a recently blogged section', function () {
    get('/')->assertSee('Sur mon blog');
});

it('has a portfolio section', function () {
    get('/')->assertSee('Portfolio');
});

it('has a contact section', function () {
    get('/')->assertSee('Contact');
});

it('has a footer', function () {
    get('/')->assertSee('Si vous lisez ceci');
});
