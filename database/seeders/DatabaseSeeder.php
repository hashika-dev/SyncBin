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
                'role' => 'admin',
            ]
        );

        // Create SuperAdmin User
        User::updateOrCreate(
            ['email' => 'superadmin@wastesync.com'],
            [
                'name' => 'SuperAdmin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );

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
                        ['name' => 'Used Battery', 'icon' => '🔋', 'weight' => '80g'],
                        ['name' => 'Expired Medicine', 'icon' => '💊', 'weight' => '15g'],
                        ['name' => 'Light Bulb', 'icon' => '💡', 'weight' => '120g'],
                    ]);
                } elseif ($bin->slug === 'recyclable') {
                    $bin->items()->createMany([
                        ['name' => 'Plastic Bottle', 'icon' => '🍼', 'weight' => '120g'],
                        ['name' => 'Paper Box', 'icon' => '📄', 'weight' => '200g'],
                        ['name' => 'Aluminum Can', 'icon' => '🥫', 'weight' => '50g'],
                    ]);
                } elseif ($bin->slug === 'biodegradable') {
                    $bin->items()->createMany([
                        ['name' => 'Banana Peel', 'icon' => '🍌', 'weight' => '45g'],
                        ['name' => 'Apple Core', 'icon' => '🍎', 'weight' => '30g'],
                        ['name' => 'Carrot Top', 'icon' => '🥕', 'weight' => '15g'],
                    ]);
                } elseif ($bin->slug === 'non-bio') {
                    $bin->items()->createMany([
                        ['name' => 'Plastic Wrap', 'icon' => '🍬', 'weight' => '10g'],
                        ['name' => 'Styrofoam Piece', 'icon' => '📦', 'weight' => '25g'],
                        ['name' => 'Broken Glass', 'icon' => '🍷', 'weight' => '60g'],
                    ]);
                }
            }
        }

        $this->command->info('Admin, SuperAdmin accounts, and default Bins seeded successfully!');
    }
}
