<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\Tests\Unit;

use Ateliware\Pokeapi\DTOs\PokemonHitPoint;
use Tests\TestCase;

final class PokemonHitPointTest extends TestCase
{
    public function test_it_can_round_trip_from_array_to_array(): void
    {
        $data = [
            'name' => 'bulbasaur',
            'hp' => 45,
        ];

        $pokemonHitPoint = PokemonHitPoint::fromArray($data);

        $this->assertSame($data, $pokemonHitPoint->toArray());
    }

    public function test_it_can_round_trip_from_object_to_array_and_back(): void
    {
        $pokemonHitPoint = new PokemonHitPoint(
            name: 'ivysaur',
            hp: 60,
        );

        $roundTripPokemonHitPoint = PokemonHitPoint::fromArray($pokemonHitPoint->toArray());

        $this->assertEquals($pokemonHitPoint, $roundTripPokemonHitPoint);
    }
}
