<?php

namespace Database\Factories;

use App\Constants\AccountConstants;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'balance' => rand(AccountConstants::INITIAL_AMOUNT, AccountConstants::INITIAL_AMOUNT * 2),
        ];
    }

}
