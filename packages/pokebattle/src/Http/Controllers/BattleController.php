<?php

declare(strict_types=1);

namespace Ateliware\Pokebattle\Http\Controllers;

use Ateliware\Pokeapi\Clients\PokeApiClient;
use Ateliware\Pokeapi\Models\Pokemon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class BattleController
{

    public function index(): Response
    {
        return Inertia::render('Battle', [
            'concorrents' => 2,
        ]);
    }

    public function getNames(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => ['nullable', 'string', 'max:255'],
        ]);

        $query = mb_strtolower((string) ($validated['query'] ?? ''));

        $suggestions = Pokemon::query()
            ->select('name')
            ->when($query !== '', fn ($pokemonQuery) => $pokemonQuery->where('name', 'like', $query.'%'))
            ->orderBy('name')
            ->limit(10)
            ->pluck('name');

        return response()->json([
            'suggestions' => $suggestions,
        ]);
    }

    public function getPokemon(Request $request, PokeApiClient $pokeApiClient): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $pokemon = $pokeApiClient->getPokemon(mb_strtolower($validated['name']));

        return response()->json($pokemon->toArray());
    }
}
