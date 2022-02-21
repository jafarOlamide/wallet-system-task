<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WalletTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type_name' => $this->faker->randomElement(['gold', 'silver', 'premium']),
            'monthly_interest' => $this->faker->randomDigit([1, 2, 3]),
            'minimum_balance'      => $this->faker->numberBetween(30000, 500000),
        ];
    }
}
