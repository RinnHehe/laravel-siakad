<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeeGroup>
 */
class FeeGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        return [
            'group' => $this->faker->numberBetween(1, 10),
            'amount' => $this->faker->numberBetween(1000000, 6000000),
        ];
    }
}
