<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_admin_can_see_users_list(): void
    {
        $this->actingAsAdmin();
        $users = User::factory()->count(10)->create();
        $this->assertDatabaseCount('users', 11);//+admin
        $response = $this->get(route('users.index'));
        $response->assertOk();
        $this->assertCount(11, $response['data']);
        $response->assertJsonFragment(["name" => $users[3]['name']]);
        $response->assertJsonFragment(["email" => $users[3]['email']]);
    }

    /**
     * @return void
     */
    public function test_customer_cannot_see_users_list(): void
    {
        $this->actingAsCustomer();
        User::factory()->count(10)->create();
        $response = $this->get(route('users.index'));
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_guest_cannot_see_users_list(): void
    {
        User::factory()->count(10)->create();
        $response = $this->get(route('users.index'));
        $response->assertStatus(302);
    }
}
