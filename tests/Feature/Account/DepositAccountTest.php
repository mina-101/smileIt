<?php

namespace Tests\Feature;

use App\Constants\UserConstants;
use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepositAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test deposit works with rational amount for admin
     */
    public function test_deposit_from_account_works_correctly_for_admin(): void
    {
        $this->actingAsAdmin();
        $sourceAccount = Account::factory()->create();
        $sourceAccountBalance = $sourceAccount->balance;
        $destinationAccount = Account::factory()->create();
        $destinationAccountBalance = $destinationAccount->balance;

        $data = [
            "source_account" => $sourceAccount->id,
            "destination_account" => $destinationAccount->id,
            "amount" => 100,
        ];

        $response = $this->post(route('accounts.deposit'), $data);
        $response->assertOk();
        $destinationAccount->refresh();
        $sourceAccount->refresh();
        $this->assertEquals($sourceAccountBalance - $data['amount'], $sourceAccount->balance);
        $this->assertEquals($destinationAccountBalance + $data['amount'], $destinationAccount->balance);
    }

    /**
     * Test deposit works with rational amount for customer
     */
    public function test_deposit_from_account_works_correctly_for_customer(): void
    {
        $user = User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]);
        $this->actingAsUser($user);
        $sourceAccount = Account::factory()->for($user)->create();
        $sourceAccountBalance = $sourceAccount->balance;
        $destinationAccount = Account::factory()->create();
        $destinationAccountBalance = $destinationAccount->balance;

        $data = [
            "source_account" => $sourceAccount->id,
            "destination_account" => $destinationAccount->id,
            "amount" => 100,
        ];

        $response = $this->post(route('accounts.deposit'), $data);
        $response->assertOk();
        $destinationAccount->refresh();
        $sourceAccount->refresh();
        $this->assertEquals($sourceAccountBalance - $data['amount'], $sourceAccount->balance);
        $this->assertEquals($destinationAccountBalance + $data['amount'], $destinationAccount->balance);
    }

    /**
     * Test guest user can not deposit
     */
    public function test_guest_can_not_deposit_from_account(): void
    {
        $sourceAccount = Account::factory()->create();
        $sourceAccountBalance = $sourceAccount->balance;
        $destinationAccount = Account::factory()->create();
        $destinationAccountBalance = $destinationAccount->balance;

        $data = [
            "source_account" => $sourceAccount->id,
            "destination_account" => $destinationAccount->id,
            "amount" => 100,
        ];

        $response = $this->post(route('accounts.deposit'), $data);
        $response->assertStatus(302);
        $destinationAccount->refresh();
        $sourceAccount->refresh();
        $this->assertEquals($sourceAccountBalance, $sourceAccount->balance);
        $this->assertEquals($destinationAccountBalance, $destinationAccount->balance);
    }

    /**
     * Test deposit does not work when amount is more than account balance limitation
     */
    public function test_deposit_obey_account_balance_limitation(): void
    {
        $user = User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]);
        $this->actingAsUser($user);
        $sourceAccount = Account::factory()->for($user)->create();
        $sourceAccountBalance = $sourceAccount->balance;
        $destinationAccount = Account::factory()->create();
        $destinationAccountBalance = $destinationAccount->balance;

        $data = [
            "source_account" => $sourceAccount->id,
            "destination_account" => $destinationAccount->id,
            "amount" => $sourceAccountBalance * 3,
        ];

        $response = $this->post(route('accounts.deposit'), $data);
        $response->assertStatus(422);
        $response->assertJsonFragment(["message" => "Account balance is low."]);
        //check balance of accounts don't change
        $destinationAccount->refresh();
        $sourceAccount->refresh();
        $this->assertEquals($sourceAccountBalance, $sourceAccount->balance);
        $this->assertEquals($destinationAccountBalance, $destinationAccount->balance);
    }

    /**
     * @return void
     */
    public function test_customer_can_not_deposit_from_other_users_account(): void
    {
        $firstUser = User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]);
        $secondUser = User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]);
        $this->actingAsUser($firstUser);

        $sourceAccount = Account::factory()->for($secondUser)->create();

        $destinationAccount = Account::factory()->for($secondUser)->create();

        $data = [
            "source_account" => $sourceAccount->id,
            "destination_account" => $destinationAccount->id,
            "amount" => 10000,
        ];

        $response = $this->post(route('accounts.deposit'), $data);

        $response->assertStatus(403);
    }
}
