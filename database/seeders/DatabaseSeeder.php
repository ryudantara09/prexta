<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if environment variables are set to create the default admin user.
        if (env('DEFAULT_ADMIN_EMAIL') && env('DEFAULT_ADMIN_PASSWORD')) {
            User::firstOrCreate(
                ['email' => env('DEFAULT_ADMIN_EMAIL')],
                [
                    'name' => env('DEFAULT_ADMIN_NAME', 'Admin'),
                    'password' => Hash::make(env('DEFAULT_ADMIN_PASSWORD')),
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
