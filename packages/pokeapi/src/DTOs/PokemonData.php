<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\DTOs;

final readonly class PokemonData
{
    public function __construct(
        public string $name,
        public string $url,
    ) {
    }

    /**
     * @param array{name: string, url: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            url: $data['url'],
        );
    }

    /**
     * @return array{name: string, url: string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
        ];
    }
}
