<?php

use Ateliware\Pokebattle\Http\Controllers\BattleConfigurationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Home', [
    'message' => 'Olá, Inertia + Vue!',
]));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [BattleConfigurationController::class, 'edit'])->name('dashboard');
    Route::put('/dashboard/battle-configuration', [BattleConfigurationController::class, 'update'])
        ->name('battle-configuration.update');
});
