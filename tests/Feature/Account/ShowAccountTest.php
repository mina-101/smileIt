<?php

namespace Tests\Feature;

use App\Constants\UserConstants;
use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_show_specific_account_information_works_correctly_for_admin(): void
    {
        $this->actingAsAdmin();
        $account = Account::factory()->create();
        $this->assertDatabaseHas('accounts', ['user_id' => $account['user_id'], 'balance' => $account['balance']]);
        $response = $this->get(route('accounts.show', $account));
        $response->assertOk();
        $response->assertJsonFragment(["user_id" => $account['user_id']]);
        $response->assertJsonFragment(["balance" => $account['balance']]);
    }

    /**
     * @return void
     */
    public function test_show_specific_account_information_works_correctly_for_customer(): void
    {
        $user = User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]);
        $this->actingAsUser($user);
        $account = Account::factory()->for($user)->create();
        $this->assertDatabaseHas('accounts', ['user_id' => $account['user_id'], 'balance' => $account['balance']]);
        $response = $this->get(route('accounts.show', $account));
        $response->assertOk();
        $response->assertJsonFragment(["user_id" => $account['user_id']]);
        $response->assertJsonFragment(["balance" => $account['balance']]);
    }

    /**
     * @return void
     */
    public function test_guest_can_not_see_specific_account_information_(): void
    {
        $account = Account::factory()->create();
        $this->assertDatabaseHas('accounts', ['user_id' => $account['user_id'], 'balance' => $account['balance']]);
        $response = $this->get(route('accounts.show', $account));
        $response->assertStatus(302);
    }

    /**
     * @return void
     */
    public function test_customers_cannot_see_other_users_accounts(): void
    {
        $firstUser = User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]);
        $secondUser = User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]);
        $this->actingAsUser($firstUser);
        $secondUserAccount = Account::factory()->for($secondUser)->create();
        $response = $this->get(route('accounts.show', $secondUserAccount));
        $response->assertStatus(403);
    }
}
