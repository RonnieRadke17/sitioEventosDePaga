<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sub;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sub>
 */
class SubFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence
        ];
    }
}
