<?php

namespace Database\Seeders;

use App\Models\User;
use App\Options\UserRoleTypes;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;

class UserSeeder extends Seeder
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
        $this->truncate('users');

        User::factory()->count(10)->create();   
        User::factory()->create(['email'=> 'jafar@1.com','role' => UserRoleTypes::ADMIN]);   
        $this->enableForeignKeys();

    }
}
