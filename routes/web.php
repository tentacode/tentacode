<?php

use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::get('/', App\Http\Controllers\LandingController::class)->name('landing');
Route::post('/contact', App\Http\Controllers\ContactController::class)->name('contact')->middleware(ProtectAgainstSpam::class);
;
Route::get('/blog', App\Http\Controllers\BlogListController::class)->name('blog.list');
Route::get('/{blogSlug}', App\Http\Controllers\BlogDetailController::class)->name('blog.detail');
