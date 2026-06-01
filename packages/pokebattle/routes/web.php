<?php

use Ateliware\Pokebattle\Http\Controllers\BattleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::controller(BattleController::class)->group(function () {
    Route::get('/battle', 'index')->name('battle.index');
    Route::get('/battle/names', 'getNames')->name('battle.names');
    Route::get('/battle/pokemon', 'getPokemon')->name('battle.pokemon');
});
