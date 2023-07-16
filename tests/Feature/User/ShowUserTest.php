<?php

namespace Tests\Feature;

use App\Constants\UserConstants;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test show specific user works correctly
     */
    public function test_admin_can_see_specific_user_information_works_correctly(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
        $response = $this->get(route('users.show', $user));
        $response->assertOk();
        $response->assertJsonFragment(["name" => $user['name']]);
        $response->assertJsonFragment(["email" => $user['email']]);
    }

    /**
     * @return void
     */
    public function test_customer_can_see_his_information(): void
    {
        $this->actingAsCustomer();
        $user = User::find(Auth::user()->id);
        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
        $response = $this->get(route('users.show', Auth::user()->id));
        $response->assertOk();
        $response->assertJsonFragment(["name" => $user['name']]);
        $response->assertJsonFragment(["email" => $user['email']]);
    }

    /**
     * @return void
     */
    public function test_customer_cannot_see_other_users_information(): void
    {
        $this->actingAsCustomer();
        $user = User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]);
        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
        $response = $this->get(route('users.show', $user->id));
        $response->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_guest_cannot_see_users_information(): void
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
        $response = $this->get(route('users.show', $user->id));
        $response->assertStatus(302);
    }

    /**
     * @return void
     */
    public function test_show_specific_user_information_returns_not_found_is_user_does_not_exists(): void
    {
        $this->actingAsAdmin();
        $response = $this->get(route('users.show', fake()->randomNumber()));
        $response->assertStatus(404);
    }
}
