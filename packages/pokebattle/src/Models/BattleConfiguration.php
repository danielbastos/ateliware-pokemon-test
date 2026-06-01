<?php

declare(strict_types=1);

namespace Ateliware\Pokebattle\Models;

use Illuminate\Database\Eloquent\Model;

final class BattleConfiguration extends Model
{
    public const DefaultConcorrents = 2;

    public $timestamps = false;

    protected $table = 'battle_configurations';

    protected $fillable = [
        'concorrents',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'concorrents' => 'integer',
        ];
    }

    public static function current(): self
    {
        return self::query()->firstOrCreate([], [
            'concorrents' => self::DefaultConcorrents,
        ]);
    }
}
