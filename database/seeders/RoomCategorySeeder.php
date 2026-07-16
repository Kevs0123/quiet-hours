<?php

namespace Database\Seeders;

use App\Models\RoomCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoomCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Standard Room',
                'description' => 'Comfortable and affordable rooms for solo travelers or couples.',
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'Spacious rooms with upgraded amenities and a scenic view.',
            ],
            [
                'name' => 'Executive Suite',
                'description' => 'Premium suites designed for business travelers who need extra space.',
            ],
            [
                'name' => 'Family Room',
                'description' => 'Large rooms that comfortably fit families with children.',
            ],
            [
                'name' => 'Presidential Suite',
                'description' => 'The most luxurious accommodation with exclusive perks and a private lounge.',
            ],
        ];

        foreach ($categories as $category) {
            RoomCategory::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }

        // A few extra randomly generated categories for variety
        RoomCategory::factory()->count(3)->create();
    }
}
