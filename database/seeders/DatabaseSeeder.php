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
        $this->seedAccounts();

        // Create default Bins
        $bins = [
            [
                'slug' => 'hazardous',
                'name' => 'Hazardous',
                'subtitle' => 'Toxic & Chemical Waste Bin',
                'color' => 'red',
                'level' => 30,
                'status' => 'Stable',
                'last_emptied_at' => now()->subHour(),
            ],
            [
                'slug' => 'recyclable',
                'name' => 'Recyclable',
                'subtitle' => 'Recoverable Waste Bin',
                'color' => 'sky',
                'level' => 90,
                'status' => 'Critical',
                'last_emptied_at' => now()->subMinutes(45),
            ],
            [
                'slug' => 'biodegradable',
                'name' => 'Biodegradable',
                'subtitle' => 'Organic Waste Bin',
                'color' => 'emerald',
                'level' => 75,
                'status' => 'High',
                'last_emptied_at' => now()->subHours(2),
            ],
            [
                'slug' => 'non-bio',
                'name' => 'Non-Biodegradable',
                'subtitle' => 'General Waste Bin',
                'color' => 'orange',
                'level' => 45,
                'status' => 'Stable',
                'last_emptied_at' => now()->subHours(5),
            ],
        ];

        foreach ($bins as $binData) {
            $bin = \App\Models\Bin::updateOrCreate(
                ['slug' => $binData['slug']],
                \Illuminate\Support\Arr::except($binData, ['last_emptied_at']) + [
                    'last_emptied_at' => $binData['last_emptied_at']
                ]
            );

            // Seed some mock items for demonstration if the bin has 0 items
            if ($bin->items()->count() === 0) {
                if ($bin->slug === 'hazardous') {
                    $bin->items()->createMany([
                        ['name' => 'Used Battery', 'icon' => '🔋'],
                        ['name' => 'Expired Medicine', 'icon' => '💊'],
                        ['name' => 'Light Bulb', 'icon' => '💡'],
                    ]);
                } elseif ($bin->slug === 'recyclable') {
                    $bin->items()->createMany([
                        ['name' => 'Plastic Bottle', 'icon' => '🍼'],
                        ['name' => 'Paper Box', 'icon' => '📄'],
                        ['name' => 'Aluminum Can', 'icon' => '🥫'],
                    ]);
                } elseif ($bin->slug === 'biodegradable') {
                    $bin->items()->createMany([
                        ['name' => 'Banana Peel', 'icon' => '🍌'],
                        ['name' => 'Apple Core', 'icon' => '🍎'],
                        ['name' => 'Carrot Top', 'icon' => '🥕'],
                    ]);
                } elseif ($bin->slug === 'non-bio') {
                    $bin->items()->createMany([
                        ['name' => 'Plastic Wrap', 'icon' => '🍬'],
                        ['name' => 'Styrofoam Piece', 'icon' => '📦'],
                        ['name' => 'Broken Glass', 'icon' => '🍷'],
                    ]);
                }
            }
        }

        $this->command?->info('Admin, SuperAdmin accounts, and default Bins seeded successfully!');
    }

    /**
     * Create the built-in Admin and SuperAdmin accounts.
     *
     * Locally they use "password". In production the passwords come from
     * SEED_ADMIN_PASSWORD / SEED_SUPERADMIN_PASSWORD, and an existing account
     * keeps whatever password its owner set, unless it is still "password".
     */
    private function seedAccounts(): void
    {
        $production = app()->isProduction();

        $passwords = $production
            ? config('app.seed_passwords')
            : ['admin' => 'password', 'superadmin' => 'password'];

        $accounts = [
            ['email' => 'admin@ecosync.com', 'name' => 'EcoSync Admin', 'role' => 'admin'],
            ['email' => 'admin@wastesync.com', 'name' => 'Admin', 'role' => 'admin'],
            ['email' => 'superadmin@ecosync.com', 'name' => 'EcoSync SuperAdmin', 'role' => 'superadmin'],
            ['email' => 'superadmin@wastesync.com', 'name' => 'SuperAdmin', 'role' => 'superadmin'],
        ];

        foreach ($accounts as $account) {
            $password = $passwords[$account['role']] ?? null;

            if (blank($password)) {
                $this->command?->warn("Skipping {$account['email']}: SEED_" . strtoupper($account['role']) . '_PASSWORD is not set.');

                continue;
            }

            $user = User::firstOrNew(['email' => $account['email']]);
            $user->name = $account['name'];
            $user->role = $account['role'];
            $user->email_verified_at ??= now();

            $usesDefault = $user->exists && Hash::check('password', $user->password);

            if (! $user->exists || ! $production || $usesDefault) {
                $user->password = Hash::make($password);
            }

            $user->save();
        }
    }
}
