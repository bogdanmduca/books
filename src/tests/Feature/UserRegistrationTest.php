<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_when_user_registers_then_an_account_is_created()
    {
        $this->withoutExceptionHandling();

        $userRaw = User::factory()->raw();

        $attributes = [
            'name' => $userRaw['name'],
            'email' => $userRaw['email'],
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post('/register', $attributes);

        $this->assertDatabaseHas('accounts', ['name' => $attributes['name']]);
    }

    public function test_when_user_registers_then_he_is_associated_to_new_account()
    {
        $this->withoutExceptionHandling();

        $userRaw = User::factory()->raw();

        $attributes = [
            'name' => $userRaw['name'],
            'email' => $userRaw['email'],
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post('/register', $attributes);

        $account = Account::where('name', $attributes['name'])->first();

        $this->assertDatabaseHas(
            'users',
            [
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'account_id' => $account->id
            ]
        );
    }
}
