<?php

namespace Database\Factories;

use App\Constants\AccountConstants;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
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
            'balance' => rand(AccountConstants::INITIAL_AMOUNT, AccountConstants::INITIAL_AMOUNT * 2),
        ];
    }

}
