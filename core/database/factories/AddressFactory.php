<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type       = array_rand(['shipper', 'receiver']);
        return [
            'name'      => fake()->name,
            'attn'      => fake()->name,
            'street'    => fake()->address,
            'zip'       => fake()->postcode,
            'city'       => fake()->city,
            'country'   => fake()->country,
            'phone'     => fake()->e164PhoneNumber,
            'email'     => fake()->email,
            'type'      => $type,
            'created_at' => now(),
            'updated_at' => now(),
          ];
    }
}
