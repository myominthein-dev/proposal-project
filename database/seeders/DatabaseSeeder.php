<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'role' => 'admin',
            'email_verified_at' => Carbon::now()
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'user@gmail.com',
            'password' => 'password',
            'role' => 'user',
            'email_verified_at' => Carbon::now()
        ]);

        User::create([
            'name' => 'Test User Two',
            'email' => 'user2@gmail.com',
            'password' => 'password',
            'role' => 'user',
            'email_verified_at' => Carbon::now()
        ]);
    }
}
