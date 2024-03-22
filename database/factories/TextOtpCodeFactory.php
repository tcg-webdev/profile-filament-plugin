<?php

declare(strict_types=1);

namespace Rawilk\ProfileFilament\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Rawilk\ProfileFilament\Models\TextOtpCode;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Rawilk\ProfileFilament\Models\AuthenticatorApp>
 */
class TextOtpCodeFactory extends Factory
{
    protected $model = TextOtpCode::class;

    public function definition(): array
    {
        return [
            'number' => fake()->phoneNumber(),
        ];
    }
}
