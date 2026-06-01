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
        ]);
        Pokemon::create([
            'name' => 'pichu',
        ]);
        Pokemon::create([
            'name' => 'bulbasaur',
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
        config()->set('inertia.testing.page_paths', [
            ...config('inertia.testing.page_paths', []),
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

        $this->assertDatabaseHas('pokemon', [
            'name' => 'pikachu',
        ]);
    }

    public function test_it_dont_create_pokemon_when_api_returns_an_error(): void
    {
        $base_url = 'https://pokeapia.test/api/v2';
        config()->set('pokeapi.base_url', $base_url);

        Http::fake([
            "{$base_url}/pokemon/missingno" => Http::response([], 404),
        ]);

        $response = $this->getJson('/battle/pokemon?name=missingno');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Pokemon not found.',
            ]);

        $this->assertDatabaseMissing('pokemon', [
            'name' => 'missingno',
        ]);
    }
}
