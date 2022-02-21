<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WalletType;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'user_id' => User::factory(),
            'wallet_type_id' => WalletType::factory(),
            'balance' => $this->faker->numberBetween(10000, 2000000),
        ];
    }
}
