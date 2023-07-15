<?php

namespace Database\Seeders;

use App\Constants\UserConstants;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'id' => "1",
                'name' => "Arisha Barron",
                'role' => UserConstants::ROLE_CUSTOMER,
                'email' => "Arisha_Barron@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('123456'),
                'remember_token' => Str::random(10),
            ],
            [
                'id' => "2",
                'name' => "Branden Gibson",
                'role' => UserConstants::ROLE_CUSTOMER,
                'email' => "Branden_Gibson@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('123456'),
                'remember_token' => Str::random(10),
            ],
            [
                'id' => "3",
                'name' => "Rhonda Church",
                'role' => UserConstants::ROLE_CUSTOMER,
                'email' => "Rhonda_Church@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('123456'),
                'remember_token' => Str::random(10),
            ],
            [
                'id' => "4",
                'name' => "Georgina Hazel",
                'role' => UserConstants::ROLE_CUSTOMER,
                'email' => "Georgina_Hazel@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('123456'),
                'remember_token' => Str::random(10),
            ]
        ];

        User::insert($customers);
    }
}
