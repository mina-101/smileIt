<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                CustomerSeeder::class,
                AdminSeeder::class
            ]
        );

        Account::factory(10)->create();
    }
}
