<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Constants\UserConstants;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test create customer works correctly
     */
    public function test_creating_user_works_correctly(): void
    {
        $data = [
            "name" => fake()->name,
            "email" => fake()->email,
            "password" => fake()->password
        ];

        $createResponse = $this->post(route('users.store'), $data);
        $createResponse->assertStatus(200);
        $this->assertDatabaseHas('users', ['name' => $data['name'], 'email' => $data['email'], 'role' => UserConstants::ROLE_CUSTOMER]);
    }

    /**
     * test create admin works correctly
     */
    public function test_creating_admin_works_correctly(): void
    {
        $data = [
            "name" => fake()->name,
            "email" => fake()->email,
            "password" => fake()->password
        ];

        $createResponse = $this->post(route('users.admin.store'), $data);
        $createResponse->assertStatus(200);
        $this->assertDatabaseHas('users', ['name' => $data['name'], 'email' => $data['email'], 'role' => UserConstants::ROLE_ADMIN]);
    }
}
