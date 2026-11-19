<?php

namespace Database\Factories;

use App\Models\Dish;
use App\Models\Eatery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dish>
 */
class DishFactory extends Factory
{
    public function definition(): array
    {
        return [
            'eatery_id' => Eatery::factory(),
            'name' => fake()->unique()->words(2, true),
            'price' => fake()->randomFloat(2, 40, 350),
            'is_bestseller' => fake()->boolean(20),
        ];
    }
}
