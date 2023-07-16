<?php

namespace Tests;

use App\Constants\UserConstants;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @return void
     */
    function actingAsAdmin()
    {
        Sanctum::actingAs(
            User::factory()->create(['role' => UserConstants::ROLE_ADMIN]),
            ['*']
        );
    }

    /**
     * @return void
     */
    function actingAsCustomer()
    {
        Sanctum::actingAs(
            User::factory()->create(['role' => UserConstants::ROLE_CUSTOMER]),
            ['*']
        );
    }

    /**
     * @param User $user
     * @return void
     */
    function actingAsUser(User $user)
    {
        Sanctum::actingAs(
            $user,
            ['*']
        );
    }
}
