<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepositAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test deposit works with rational amount
     */
    public function test_deposit_from_account_works_correctly(): void
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
        $response->assertOk();
        $destinationAccount->refresh();
        $sourceAccount->refresh();
        $this->assertEquals($sourceAccountBalance - $data['amount'], $sourceAccount->balance);
        $this->assertEquals($destinationAccountBalance + $data['amount'], $destinationAccount->balance);
    }

    /**
     * Test deposit does not work when amount is more than account balance limitation
     */
    public function test_deposit_obey_account_balance_limitation(): void
    {
        $sourceAccount = Account::factory()->create();
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
}
