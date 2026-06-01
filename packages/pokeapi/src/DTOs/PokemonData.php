<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\DTOs;

final readonly class PokemonData
{
    public function __construct(
        public string $name,
    ) {}

    /**
     * @param  array{name: string}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
        );
    }

    /**
     * @return array{name: string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
