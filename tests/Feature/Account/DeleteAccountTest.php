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
     * @return void
     */
    public function test_admin_can_remove_accounts(): void
    {
        $this->actingAsAdmin();
        $account = Account::factory()->create();
        $this->assertDatabaseHas('accounts', ['user_id' => $account['user_id'], 'balance' => $account['balance']]);
        $response = $this->delete(route('accounts.destroy', $account));
        $response->assertOk();
        $this->assertSoftDeleted($account);
    }

    /**
     * @return void
     */
    public function test_customer_can_not_remove_accounts(): void
    {
        $this->actingAsCustomer();
        $account = Account::factory()->create();
        $this->assertDatabaseHas('accounts', ['user_id' => $account['user_id'], 'balance' => $account['balance']]);
        $response = $this->delete(route('accounts.destroy', $account));
        $response->assertStatus(403);//authorization
        $this->assertDatabaseHas('accounts', ['user_id' => $account['user_id'], 'balance' => $account['balance']]);
    }

    /**
     * @return void
     */
    public function test_guest_can_not_remove_accounts(): void
    {
        $account = Account::factory()->create();
        $this->assertDatabaseHas('accounts', ['user_id' => $account['user_id'], 'balance' => $account['balance']]);
        $response = $this->delete(route('accounts.destroy', $account));
        $response->assertStatus(302);//authentication
        $this->assertDatabaseHas('accounts', ['user_id' => $account['user_id'], 'balance' => $account['balance']]);
    }
}
