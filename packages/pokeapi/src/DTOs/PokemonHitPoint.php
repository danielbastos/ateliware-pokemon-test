<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\DTOs;

final readonly class PokemonHitPoint
{
    public function __construct(
        public string $name,
        public int $hp,
        public string $image_url,
    ) {
    }

    /**
     * @param array{name: string, hp: int, image_url: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            hp: $data['hp'],
            image_url: $data['image_url'],
        );
    }

    /**
     * @return array{name: string, hp: int, image_url: string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'hp' => $this->hp,
            'image_url' => $this->image_url
        ];
    }
}
