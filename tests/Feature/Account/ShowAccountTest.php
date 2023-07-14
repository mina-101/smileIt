<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_show_specific_account_information_works_correctly(): void
    {
        $account = Account::factory()->create();
        $this->assertDatabaseHas('accounts', ['user_id' => $account['user_id'], 'balance' => $account['balance']]);
        $response = $this->get(route('accounts.show', $account));
        $response->assertOk();
        $response->assertJsonFragment(["user_id" => $account['user_id']]);
        $response->assertJsonFragment(["balance" => $account['balance']]);
    }
}
