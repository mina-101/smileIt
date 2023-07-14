<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_list_account_works_correctly(): void
    {
        $account = Account::factory()->count(10)->create();
        $this->assertDatabaseCount('accounts', 10);
        $response = $this->get(route('accounts.index'));
        $response->assertOk();
        $this->assertCount(10 , $response['data']);
        $response->assertJsonFragment(["user_id" => $account[3]['user_id']]);
        $response->assertJsonFragment(["balance" => $account[3]['balance']]);
    }
}
