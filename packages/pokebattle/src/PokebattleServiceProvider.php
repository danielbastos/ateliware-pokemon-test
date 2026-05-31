<?php

declare(strict_types=1);

namespace Ateliware\Pokebattle;

use Illuminate\Support\ServiceProvider;

final class PokebattleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
