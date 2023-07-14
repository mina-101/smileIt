<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Constants\AccountConstants;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatAccountTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_creating_account_works_correctly(): void
    {
        $data = [
            "user_id"=> User::factory()->create()->id,
        ];

        $createResponse = $this->post(route('accounts.store'), $data);
        $createResponse->assertStatus(200);
        $this->assertDatabaseHas('accounts', ['user_id'=>$data['user_id'], 'balance'=>AccountConstants::INITIAL_AMOUNT]);
    }
}
