<?php

declare(strict_types=1);

namespace Ateliware\Pokebattle\Tests\Feature;

use Ateliware\Pokeapi\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

final class BattleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_suggestions_name(): void
    {
        Pokemon::create([
            'name' => 'pikachu',
            'url' => 'https://pokeapi.co/api/v2/pokemon/25/',
        ]);
        Pokemon::create([
            'name' => 'pichu',
            'url' => 'https://pokeapi.co/api/v2/pokemon/172/',
        ]);
        Pokemon::create([
            'name' => 'bulbasaur',
            'url' => 'https://pokeapi.co/api/v2/pokemon/1/',
        ]);

        $response = $this->getJson('/battle/names?query=pi');

        $response->assertOk()
            ->assertJson([
                'suggestions' => [
                    'pichu',
                    'pikachu',
                ],
            ]);
    }

    public function test_it_renders_battle_page(): void
    {
        config()->set('inertia.pages.paths', [
            ...config('inertia.pages.paths', []),
            base_path('packages/pokebattle/resources/js/Pages'),
        ]);

        $response = $this->get(route('battle.index'));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Battle')
                ->where('concorrents', 2)
            );
    }

    public function test_it_returns_pokemon_hit_points(): void
    {
        $base_url = 'https://pokeapia.test/api/v2';
        config()->set('pokeapi.base_url', $base_url);

        Http::fake([
            "{$base_url}/pokemon/pikachu" => Http::response([
                'name' => 'pikachu',
                'stats' => [
                    [
                        'base_stat' => 35,
                        'stat' => [
                            'name' => 'hp',
                        ],
                        'image_url' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/home/female/25.png',
                    ],
                ],
                'sprites' => [
                    'front_default' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/home/female/25.png',
                    'other' => [
                        'home' => [
                            'front_default' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/home/female/25.png',
                        ],
                    ],
                ],
            ]),
        ]);

        $response = $this->getJson('/battle/pokemon?name=Pikachu');

        $response->assertOk()
            ->assertJson([
                'name' => 'pikachu',
                'hp' => 35,
                'image_url' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/home/female/25.png',
            ]);
    }
}
