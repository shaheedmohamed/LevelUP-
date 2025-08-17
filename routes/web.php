<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// SPA fallback to support Vue Router routes on refresh
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
