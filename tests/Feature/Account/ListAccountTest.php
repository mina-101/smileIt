<?php

namespace Tests\Feature;

use App\Constants\UserConstants;
use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_list_account_works_correctly_for_admin(): void
    {
        $this->actingAsAdmin();
        $account = Account::factory()->count(10)->create();
        $this->assertDatabaseCount('accounts', 10);
        $response = $this->get(route('accounts.index'));
        $response->assertOk();
        $this->assertCount(10, $response['data']);
        $response->assertJsonFragment(["user_id" => $account[3]['user_id']]);
        $response->assertJsonFragment(["balance" => $account[3]['balance']]);
    }

    /**
     * @return void
     */
    public function test_list_account_works_correctly_for_customer(): void
    {
        $firstUser = User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]);
        $secondUser = User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]);
        $this->actingAsUser($firstUser);
        $firstUserAccounts = Account::factory()->for($firstUser)->count(5)->create();
        $secondUserAccounts = Account::factory()->for($secondUser)->count(3)->create();
        $this->assertDatabaseCount('accounts', 8);
        $response = $this->get(route('accounts.index'));
        $response->assertOk();
        $this->assertCount(5, $response['data']);
        $response->assertJsonFragment(["user_id" => $firstUserAccounts[3]['user_id']]);
        $response->assertJsonFragment(["balance" => $firstUserAccounts[3]['balance']]);

        $response->assertJsonMissing(["user_id" => $secondUserAccounts[0]['user_id']]);
        $response->assertJsonMissing(["balance" => $secondUserAccounts[0]['balance']]);
    }

    /**
     * @return void
     */
    public function test_guest_can_not_see_list(): void
    {
        Account::factory()->count(10)->create();
        $this->assertDatabaseCount('accounts', 10);
        $response = $this->get(route('accounts.index'));
        $response->assertStatus(302);
    }
}
