<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WalletType;
use App\Options\DefaultWalletTypes;
use App\Options\UserRoleTypes;
use Database\Seeders\WalletTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $wallet_type;
   
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        $this->seed(WalletTypeSeeder::class);

        $this->wallet_type = WalletType::where('type_name', DefaultWalletTypes::SILVER)->first();


    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_users_can_register()
    {
        
        $form_request = [
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number'=> $this->faker->phoneNumber(),
            'name' => $this->faker->name(), 
            'password' => Hash::make('1234'), 
            'wallet_type_id'=> $this->wallet_type->id,
        ]; 

        $response = $this->post('api/auth/register', $form_request);
        
        $this->assertDatabaseHas('users', [
            'name'              => $form_request['name'],
            'email'             => $form_request['email'],
            'phone'             => $form_request['phone_number'],
            'role'              => UserRoleTypes::USER, 
            'email_verified_at' => now()
        ]);

        $user = User::where('email', $form_request['email'])->first();
       

        $this->assertDatabaseHas('wallets', [
            'user_id'           => $user->id,
            'wallet_type_id' => $form_request['wallet_type_id'],
            'balance'        => '0'
        ]);

        $response->assertStatus(200);
    }
}
