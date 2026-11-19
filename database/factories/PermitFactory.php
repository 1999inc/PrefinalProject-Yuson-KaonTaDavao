<?php

namespace Database\Factories;

use App\Models\Eatery;
use App\Models\Permit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Permit>
 */
class PermitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'eatery_id' => Eatery::factory(),
            'permit_no' => 'DVO-2026-'.fake()->unique()->numberBetween(10000, 99999),
            'expires_on' => fake()->dateTimeBetween('-1 year', '+2 years')->format('Y-m-d'),
        ];
    }
}
