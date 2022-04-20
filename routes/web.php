<?php

use Illuminate\Support\Facades\Route;

Route::get('/', App\Http\Controllers\LandingController::class)->name('landing');
Route::get('/blog', App\Http\Controllers\BlogListController::class)->name('blog.list');
Route::get('/{blogSlug}', App\Http\Controllers\BlogDetailController::class)->name('blog.detail');
