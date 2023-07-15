<?php

namespace Tests\Feature;

use App\Constants\AccountConstants;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_customers_can_create_account_successfully(): void
    {
        $this->actingAsCustomer();
        $data = [
            "user_id"=> User::factory()->create()->id,
        ];

        $createResponse = $this->post(route('accounts.store'), $data);
        $createResponse->assertStatus(200);
        $this->assertDatabaseHas('accounts', ['user_id'=>$data['user_id'], 'balance'=>AccountConstants::INITIAL_AMOUNT]);
    }

    /**
     * @return void
     */
    public function test_admin_can_create_account_successfully(): void
    {
        $this->actingAsAdmin();
        $data = [
            "user_id"=> User::factory()->create()->id,
        ];

        $createResponse = $this->post(route('accounts.store'), $data);
        $createResponse->assertStatus(200);
        $this->assertDatabaseHas('accounts', ['user_id'=>$data['user_id'], 'balance'=>AccountConstants::INITIAL_AMOUNT]);
    }
}
