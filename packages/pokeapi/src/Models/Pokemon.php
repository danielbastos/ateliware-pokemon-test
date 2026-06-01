<?php

declare(strict_types=1);

namespace Ateliware\Pokeapi\Models;

use Illuminate\Database\Eloquent\Model;

final class Pokemon extends Model
{
    protected $table = 'pokemon';

    protected $fillable = [
        'name',
    ];
}
