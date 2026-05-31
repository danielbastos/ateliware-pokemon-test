<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\Commands;

use Ateliware\Pokeapi\Clients\PokeApiClient;
use Ateliware\Pokeapi\Models\Pokemon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class PokeApiNamesCommand extends Command
{
    protected $signature = 'pokeapi:names';

    protected $description = 'List Pokemon names from PokeAPI.';

    public function handle(PokeApiClient $client): int
    {
        $pokemonData = $client->getAllPokemon();

        DB::transaction(function () use ($pokemonData): void {
            $names = [];
            foreach ($pokemonData as $pokemon) {
                Pokemon::query()->updateOrCreate(
                    ['name' => $pokemon->name],
                    $pokemon->toArray(),
                );
                $names[] = $pokemon->name;
            }

            $deleted = Pokemon::whereNotIn('name', $names)->delete();
            $this->line(count($names) . ' insert/update');
            $this->line($deleted . ' deleted');
        });

        return self::SUCCESS;
    }
}
