<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create(
            [
                'name' => 'Test User',
                'password' => 'password',
                'email' => 'test@example.com',
                'email_verified_at' => now(),
                'type' => 'admin'
            ]
        );

        User::factory(5)
            ->isRegular()
            ->create();
    }
}
