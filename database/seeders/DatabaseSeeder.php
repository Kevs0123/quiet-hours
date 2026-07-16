<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin account ──────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@quiethours.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('admin1234'),
                'role'     => 'admin',
            ]
        );

        // ── Demo client account ────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'client@quiethours.com'],
            [
                'name'     => 'Maria Santos',
                'password' => Hash::make('client1234'),
                'role'     => 'client',
            ]
        );

        // ── Room data ──────────────────────────────────────────────────
        $this->call([
            RoomCategorySeeder::class,
            RoomSeeder::class,
        ]);
    }
}
