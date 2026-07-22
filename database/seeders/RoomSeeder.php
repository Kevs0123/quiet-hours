<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $imagesByCategory = [
            'standard-room' => [
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1501117716987-c8eae3f20b52?w=800&q=80&auto=format&fit=crop',
            ],
            'deluxe-room' => [
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1600585154405-0c1f6f2f6d73?w=800&q=80&auto=format&fit=crop',
            ],
            'executive-suite' => [
                'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1576675786044-c92f8b6d3f2f?w=800&q=80&auto=format&fit=crop',
            ],
            'family-room' => [
                'https://images.unsplash.com/photo-1560448204-0b3a1a4f3f5d?w=800&q=80&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1542317854-8f0f9d7a2f1b?w=800&q=80&auto=format&fit=crop',
            ],
            'presidential-suite' => [
                'https://images.unsplash.com/photo-1549187774-b4e9a4d3d1b2?w=800&q=80&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1554995207-c18c203602cb?w=800&q=80&auto=format&fit=crop',
            ],
        ];

        RoomCategory::all()->each(function (RoomCategory $category) use ($imagesByCategory) {
            $slug = \Illuminate\Support\Str::slug($category->name);
            $images = $imagesByCategory[$slug] ?? ['https://via.placeholder.com/800x600?text=Room'];

            // Create a small, curated number of rooms per category
            Room::factory()->count(2)->create([
                'room_category_id' => $category->id,
                'image_path' => $images[array_rand($images)],
            ]);
        });
    }
}
