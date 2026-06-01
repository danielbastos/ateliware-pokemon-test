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
        ]);

        $this->assertInstanceOf(Pokemon::class, $pokemon);
        $this->assertDatabaseHas($pokemon->getTable(), [
            'id' => $pokemon->id,
            'name' => 'bulbasaur',
        ]);

        $foundPokemon = Pokemon::query()->findOrFail($pokemon->id);

        $this->assertSame('bulbasaur', $foundPokemon->name);

        $foundPokemon->update([
            'name' => 'ivysaur',
        ]);

        $this->assertDatabaseHas($foundPokemon->getTable(), [
            'id' => $foundPokemon->id,
            'name' => 'ivysaur',
        ]);

        $foundPokemon->delete();

        $this->assertDatabaseMissing($foundPokemon->getTable(), [
            'id' => $foundPokemon->id,
        ]);
    }
}
