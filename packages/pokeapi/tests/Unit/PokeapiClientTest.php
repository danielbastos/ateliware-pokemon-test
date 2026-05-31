<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\Tests\Unit;

use Ateliware\Pokeapi\Clients\PokeApiClient;
use Ateliware\Pokeapi\DTOs\PokemonData;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class PokeApiClientTest extends TestCase
{
    public function test_it_returns_pokemon_from_all_pages(): void
    {
        config()->set('pokeapi.base_url', 'https://pokeapi.test/api/v2');

        Http::fake([
            'https://pokeapi.test/api/v2/pokemon' => Http::response([
                'next' => 'https://pokeapi.test/api/v2/pokemon?offset=2&limit=2',
                'results' => [
                    [
                        'name' => 'bulbasaur',
                        'url' => 'https://pokeapi.co/api/v2/pokemon/1/',
                    ],
                    [
                        'name' => 'ivysaur',
                        'url' => 'https://pokeapi.co/api/v2/pokemon/2/',
                    ],
                ],
            ]),
            'https://pokeapi.test/api/v2/pokemon?offset=2&limit=2' => Http::response([
                'next' => null,
                'results' => [
                    [
                        'name' => 'venusaur',
                        'url' => 'https://pokeapi.co/api/v2/pokemon/3/',
                    ],
                ],
            ]),
        ]);

        $pokemon = (new PokeApiClient())->getAllPokemon();

        $this->assertEquals([
            new PokemonData(
                name: 'bulbasaur',
                url: 'https://pokeapi.co/api/v2/pokemon/1/',
            ),
            new PokemonData(
                name: 'ivysaur',
                url: 'https://pokeapi.co/api/v2/pokemon/2/',
            ),
            new PokemonData(
                name: 'venusaur',
                url: 'https://pokeapi.co/api/v2/pokemon/3/',
            ),
        ], $pokemon);

        Http::assertSentCount(2);
    }

    public function test_it_returns_a_pokemon_by_name(): void
    {
        config()->set('pokeapi.base_url', 'https://pokeapi.test/api/v2');

        Http::fake([
            'https://pokeapi.test/api/v2/pokemon/pikachu' => Http::response([
                'name' => 'pikachu',
            ]),
        ]);

        $pokemon = (new PokeApiClient())->getPokemon('pikachu');

        $this->assertEquals(new PokemonData(
            name: 'pikachu',
            url: 'https://pokeapi.test/api/v2/pokemon/pikachu',
        ), $pokemon);

        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://pokeapi.test/api/v2/pokemon/pikachu';
        });
    }

    public function test_it_throws_an_exception_when_pokemon_is_not_found(): void
    {
        config()->set('pokeapi.base_url', 'https://pokeapi.test/api/v2');

        Http::fake([
            'https://pokeapi.test/api/v2/pokemon/missingno' => Http::response([], 404),
        ]);

        $this->expectException(RequestException::class);

        try {
            (new PokeApiClient())->getPokemon('missingno');
        } catch (RequestException $exception) {
            $this->assertSame(404, $exception->response->status());

            throw $exception;
        }
    }
}
