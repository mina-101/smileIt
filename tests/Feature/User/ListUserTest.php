<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_list_user_works_correctly(): void
    {
        $user = User::factory()->count(10)->create();
        $this->assertDatabaseCount('users', 10);
        $response = $this->get(route('users.index'));
        $response->assertOk();
        $this->assertCount(10 , $response['data']);
        $response->assertJsonFragment(["name" => $user[3]['name']]);
        $response->assertJsonFragment(["email" => $user[3]['email']]);
    }
}
