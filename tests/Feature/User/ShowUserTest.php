<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test show specific user works correctly
     */
    public function test_show_specific_user_information_works_correctly(): void
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
        $response = $this->get(route('users.show', $user));
        $response->assertOk();
        $response->assertJsonFragment(["name" => $user['name']]);
        $response->assertJsonFragment(["email" => $user['email']]);
    }

    /**
     * Test show user returns 404 if user does not exists
     */
    public function test_show_specific_user_information_returns_not_found_is_user_does_not_exists(): void
    {
        $response = $this->get(route('users.show', fake()->randomNumber()));
        $response->assertStatus(404);
    }
}
