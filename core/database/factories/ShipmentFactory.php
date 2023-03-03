<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'shipper_reference' => fake()->randomNumber(8, true),
            'weight'        => fake()->randomFloat(2, 0, 100),
            'description'   => join(', ', fake()->words(rand(3, 15))),
            'booking_date'          => fake()->datetimeBetween('-6 month'),
            'operator'      => fake()->word,
            'user_id'      => 1,
            'created_at'    => now()
        ];
    }
}
