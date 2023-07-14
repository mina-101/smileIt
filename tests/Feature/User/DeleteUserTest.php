<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_removing_user_works_correctly(): void
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
        $response = $this->delete(route('users.destroy', $user));
        $response->assertOk();
        $this->assertSoftDeleted($user);
    }
}
