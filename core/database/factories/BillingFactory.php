<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppModelsBilling>
 */
class BillingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $bill = ceil(mt_rand(300, 5000)/5)*5;
        $paid = fake()->randomElement([$bill, null]);
        $due  = isset($paid) ? $bill - $paid : null;
        return [
            'bill'  => $bill,
            'paid'  => $paid,
            'due'   => $due
        ];
    }
}
