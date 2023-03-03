<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agent>
 */
class AgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'      => fake()->name,
            'company'   => fake()->company,
            'phone'   => fake()->e164PhoneNumber,
            'email'   => fake()->email,
            'address'   => fake()->address,
        ];
    }
}
