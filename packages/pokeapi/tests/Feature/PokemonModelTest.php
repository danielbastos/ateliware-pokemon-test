<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\Tests\Feature;

use Ateliware\Pokeapi\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PokemonModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_perform_model_operations(): void
    {
        $pokemon = Pokemon::create([
            'name' => 'bulbasaur',
            'url' => 'https://pokeapi.co/api/v2/pokemon/1/',
        ]);

        $this->assertInstanceOf(Pokemon::class, $pokemon);
        $this->assertDatabaseHas($pokemon->getTable(), [
            'id' => $pokemon->id,
            'name' => 'bulbasaur',
            'url' => 'https://pokeapi.co/api/v2/pokemon/1/',
        ]);

        $foundPokemon = Pokemon::query()->findOrFail($pokemon->id);

        $this->assertSame('bulbasaur', $foundPokemon->name);
        $this->assertSame('https://pokeapi.co/api/v2/pokemon/1/', $foundPokemon->url);

        $foundPokemon->update([
            'name' => 'ivysaur',
            'url' => 'https://pokeapi.co/api/v2/pokemon/2/',
        ]);

        $this->assertDatabaseHas($foundPokemon->getTable(), [
            'id' => $foundPokemon->id,
            'name' => 'ivysaur',
            'url' => 'https://pokeapi.co/api/v2/pokemon/2/',
        ]);

        $foundPokemon->delete();

        $this->assertDatabaseMissing($foundPokemon->getTable(), [
            'id' => $foundPokemon->id,
        ]);
    }
}
