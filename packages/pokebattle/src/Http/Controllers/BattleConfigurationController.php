<?php

declare(strict_types=1);

namespace Ateliware\Pokebattle\Http\Controllers;

use Ateliware\Pokebattle\Http\Requests\UpdateBattleConfigurationRequest;
use Ateliware\Pokebattle\Models\BattleConfiguration;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class BattleConfigurationController
{
    public function edit(): Response
    {
        $battleConfiguration = BattleConfiguration::current();

        return Inertia::render('Dashboard', [
            'battleConfiguration' => [
                'concorrents' => $battleConfiguration->concorrents,
            ],
        ]);
    }

    public function update(UpdateBattleConfigurationRequest $request): RedirectResponse
    {
        BattleConfiguration::current()->update($request->validated());

        return redirect()->route('dashboard');
    }
}
