<?php

namespace Database\Seeders;

use App\Models\WalletType;
use App\Options\DefaultWalletTypes;
use Faker\Factory;
use Illuminate\Database\Seeder;

class WalletTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        // $types = app(DefaultWalletTypes::class)->getConstants();

        $types = [DefaultWalletTypes::GOLD, DefaultWalletTypes::PREMIUM, DefaultWalletTypes::SILVER];


        foreach ($types as $type ) {
            WalletType::firstOrCreate([
                'type_name'        => $type,
            ],[
                'minimum_balance'  => $faker->numberBetween(30000, 500000),
                'monthly_interest' => $faker->randomDigit([1, 2, 3]),       
            ]);
        }

    }
}
