<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_removing_user_works_correctly_for_admin(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
        $response = $this->delete(route('users.destroy', $user));
        $response->assertOk();
        $this->assertSoftDeleted($user);
    }

    /**
     * @return void
     */
    public function test_customers_cannot_remove_user(): void
    {
        $this->actingAsCustomer();
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
        $response = $this->delete(route('users.destroy', $user));
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
    }

    /**
     * @return void
     */
    public function test_guest_cannot_remove_user(): void
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
        $response = $this->delete(route('users.destroy', $user));
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
    }
}
