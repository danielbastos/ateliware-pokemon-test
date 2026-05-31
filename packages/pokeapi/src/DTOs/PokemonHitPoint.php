<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\DTOs;

final readonly class PokemonHitPoint
{
    public function __construct(
        public string $name,
        public int $hp,
    ) {
    }

    /**
     * @param array{name: string, hp: int} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            hp: $data['hp'],
        );
    }

    /**
     * @return array{name: string, hp: int}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'hp' => $this->hp,
        ];
    }
}
