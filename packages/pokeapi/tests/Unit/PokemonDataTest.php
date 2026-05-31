<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\Tests\Unit;

use Ateliware\Pokeapi\DTOs\PokemonData;
use Tests\TestCase;

final class PokemonDataTest extends TestCase
{
    public function test_it_can_round_trip_from_array_to_array(): void
    {
        $data = [
            'name' => 'bulbasaur',
            'url' => 'https://pokeapi.co/api/v2/pokemon/1/',
        ];

        $pokemonData = PokemonData::fromArray($data);

        $this->assertSame($data, $pokemonData->toArray());
    }

    public function test_it_can_round_trip_from_object_to_array_and_back(): void
    {
        $pokemonData = new PokemonData(
            name: 'ivysaur',
            url: 'https://pokeapi.co/api/v2/pokemon/2/',
        );

        $roundTripPokemonData = PokemonData::fromArray($pokemonData->toArray());

        $this->assertEquals($pokemonData, $roundTripPokemonData);
    }
}
