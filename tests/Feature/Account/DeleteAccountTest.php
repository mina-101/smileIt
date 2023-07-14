<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_removing_account_works_correctly(): void
    {
        $account = Account::factory()->create();
        $this->assertDatabaseHas('accounts', ['user_id' => $account['user_id'], 'balance' => $account['balance']]);
        $response = $this->delete(route('accounts.destroy', $account));
        $response->assertOk();
        $this->assertSoftDeleted($account);
    }
}
