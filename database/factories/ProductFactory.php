<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->colorName() . ' ' . fake()->word(), // Generate a random unique name for the product
            'price' => fake()->randomFloat(2, 10, 5000), // Generates a random float between 10 and 5000 with 2 decimal places
        ];
    }
}
