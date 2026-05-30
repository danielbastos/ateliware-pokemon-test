<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Home', [
    'message' => 'Olá, Inertia + Vue!',
]));

/*
Route::get('/', function () {
    return view('welcome');
});
*/