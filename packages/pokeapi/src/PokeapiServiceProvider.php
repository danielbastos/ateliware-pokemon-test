<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi;

use Ateliware\Pokeapi\Commands\PokeApiNamesCommand;
use Illuminate\Support\ServiceProvider;

final class PokeapiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pokeapi.php', 'pokeapi');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                PokeApiNamesCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/pokeapi.php' => config_path('pokeapi.php'),
        ], 'pokeapi-config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'pokeapi-migrations');
    }
}
