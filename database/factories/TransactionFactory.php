<?php

namespace Database\Factories;

use App\Constants\AccountConstants;
use App\Constants\TransactionConstants;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;


class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'amount' => rand(AccountConstants::INITIAL_AMOUNT, AccountConstants::INITIAL_AMOUNT * 2),
            'type' => rand(TransactionConstants::TYPE_DEPOSIT, TransactionConstants::TYPE_WITHDRAW),
            'uuid' => fake()->uuid,
            'balance' => rand(AccountConstants::INITIAL_AMOUNT, AccountConstants::INITIAL_AMOUNT * 2),
        ];
    }

}
