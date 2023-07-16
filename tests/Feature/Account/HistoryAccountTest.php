<?php

namespace Tests\Feature;

use App\Constants\UserConstants;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoryAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_admin_can_see_users_account_history(): void
    {
        $this->actingAsAdmin();
        $account = Account::factory()->create();
        Transaction::factory()->for($account)->count(10)->create();

        $response = $this->get(route('accounts.history', $account));
        $response->assertOk();
        $this->assertCount(10, $response['data']);
    }

    /**
     * @return void
     */
    public function test_customer_can_see_his_own_account_history(): void
    {
        $user = User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]);
        $this->actingAsUser($user);
        $account = Account::factory()->for($user)->create();
        Transaction::factory()->for($account)->count(10)->create();

        $response = $this->get(route('accounts.history', $account));
        $response->assertOk();
        $this->assertCount(10, $response['data']);
    }

    /**
     * @return void
     */
    public function test_guest_can_not_see_account_history(): void
    {
        $transaction = Transaction::factory()->create();
        $response = $this->get(route('accounts.history', $transaction->account));
        $response->assertStatus(302);
    }

    /**
     * @return void
     */
    public function test_customers_can_not_see_other_users_account_history(): void
    {
        $this->actingAsCustomer();
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        Transaction::factory()->for($account)->create();

        $response = $this->get(route('accounts.history', $account));
        $response->assertStatus(403);
    }
}
