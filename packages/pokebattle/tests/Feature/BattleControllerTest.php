<?php

declare(strict_types=1);

namespace Ateliware\Pokebattle\Tests\Feature;

use App\Models\User;
use Ateliware\Pokeapi\Models\Pokemon;
use Ateliware\Pokebattle\Models\BattleConfiguration;
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

        BattleConfiguration::create([
            'concorrents' => 4,
        ]);

        $response = $this->get(route('battle.index'));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Battle')
                ->where('concorrents', 4)
            );
    }

    public function test_it_dashboard_battle_configuration(): void
    {
        BattleConfiguration::create([
            'concorrents' => 5,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard'));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard')
                ->where('battleConfiguration.concorrents', 5)
            );
    }

    public function test_it_updates_battle_configuration(): void
    {
        BattleConfiguration::create([
            'concorrents' => 2,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('battle-configuration.update'), [
                'concorrents' => 6,
            ]);

        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('battle_configurations', [
            'concorrents' => 6,
        ]);
    }

    public function test_it_validates_battle_configuration_concorrents(): void
    {
        BattleConfiguration::create([
            'concorrents' => 3,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->from(route('dashboard'))
            ->put(route('battle-configuration.update'), [
                'concorrents' => 1,
            ]);

        $response->assertRedirect(route('dashboard', absolute: false))
            ->assertSessionHasErrors('concorrents');

        $this->assertDatabaseHas('battle_configurations', [
            'concorrents' => 3,
        ]);
    }

    public function test_unauthenticated_cannot_update_battle_configuration(): void
    {
        $response = $this->put(route('battle-configuration.update'), [
            'concorrents' => 4,
        ]);

        $response->assertRedirect(route('login', absolute: false));
    }

    public function test_unauthenticated_cannot_view_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login', absolute: false));
    }

    public function test_it_default_battle_configuration(): void
    {
        config()->set('inertia.testing.page_paths', [
            ...config('inertia.testing.page_paths', []),
            base_path('packages/pokebattle/resources/js/Pages'),
        ]);

        $response = $this->get(route('battle.index'));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Battle')
                ->where('concorrents', BattleConfiguration::DefaultConcorrents)
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
