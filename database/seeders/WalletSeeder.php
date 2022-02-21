<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletType;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->disableForeignKeys();
        $this->truncate('wallets');
        $users = User::all();
        $wallet_types = WalletType::all();

        foreach ($users as $user) {
            foreach ($wallet_types as $wallet_type) {
                Wallet::factory()->count(3)->create([
                    'user_id' => $user->id,
                    'wallet_type_id' => $wallet_type->id
                ]);
            }
        }
        $this->enableForeignKeys();
    }
}
