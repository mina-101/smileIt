<?php

namespace Database\Seeders;

use App\Constants\UserConstants;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            'name' => "admin",
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => UserConstants::ROLE_ADMIN,
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'remember_token' => Str::random(10),
        ]);
    }
}
