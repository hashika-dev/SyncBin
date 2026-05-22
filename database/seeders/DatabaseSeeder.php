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
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@wastesync.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Create SuperAdmin User
        User::updateOrCreate(
            ['email' => 'superadmin@wastesync.com'],
            [
                'name' => 'SuperAdmin',
                'password' => Hash::make('password'),
            ]
        );

        $this->command->info('Admin and SuperAdmin accounts created successfully!');
    }
}
