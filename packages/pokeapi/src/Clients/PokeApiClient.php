<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\Clients;

use Ateliware\Pokeapi\DTOs\PokemonData;
use Ateliware\Pokeapi\DTOs\PokemonHitPoint;
use Illuminate\Support\Facades\Http;

final class PokeApiClient
{
    private function pokemonUrl(): string
    {
        return sprintf('%s/pokemon', trim((string) config('pokeapi.base_url'), '/'));
    }

    /**
     * @return array<PokemonData>
     */
    public function getAllPokemon(): array
    {
        $url = $this->pokemonUrl();
        $res = [];

        while ($url !== null) {
            $response = Http::acceptJson()->get($url)->throw()->json();
            foreach ($response['results'] ?? [] as $item)
                $res[] = PokemonData::fromArray($item);
            $url = $response['next'] ?? null;
        }

        return $res;
    }

    public function getPokemon(string $name): PokemonHitPoint
    {
        $url = sprintf('%s/%s', $this->pokemonUrl(), rawurlencode($name));
        $response = Http::acceptJson()->get($url)->throw()->json();

        return new PokemonHitPoint(
            name: $response['name'],
            hp: 1,
        );
    }
}
