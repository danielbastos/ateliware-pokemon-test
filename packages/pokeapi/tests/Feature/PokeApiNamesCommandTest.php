<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\Tests\Feature;

use Ateliware\Pokeapi\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class PokeApiNamesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_syncs_pokemon_from_all_pages(): void
    {
        config()->set('pokeapi.base_url', 'https://pokeapi.test/api/v2');

        Pokemon::create([
            'name' => 'charmander',
            'url' => 'https://pokeapi.co/api/v2/pokemon/4/',
        ]);

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

        $this->artisan('pokeapi:names')
            ->expectsOutput('3 insert/update')
            ->expectsOutput('1 deleted')
            ->assertSuccessful();

        Http::assertSentCount(2);

        $this->assertDatabaseHas('pokemon', [
            'name' => 'bulbasaur',
            'url' => 'https://pokeapi.co/api/v2/pokemon/1/',
        ]);
        $this->assertDatabaseHas('pokemon', [
            'name' => 'ivysaur',
            'url' => 'https://pokeapi.co/api/v2/pokemon/2/',
        ]);
        $this->assertDatabaseHas('pokemon', [
            'name' => 'venusaur',
            'url' => 'https://pokeapi.co/api/v2/pokemon/3/',
        ]);
        $this->assertDatabaseMissing('pokemon', [
            'name' => 'charmander',
            'url' => 'https://pokeapi.co/api/v2/pokemon/4/',
        ]);
    }

}
