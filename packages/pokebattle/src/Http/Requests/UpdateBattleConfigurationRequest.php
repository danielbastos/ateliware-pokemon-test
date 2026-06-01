<?php

declare(strict_types=1);

namespace Ateliware\Pokebattle\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateBattleConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'concorrents' => ['required', 'integer', 'min:2', 'max:6'],
        ];
    }
}
