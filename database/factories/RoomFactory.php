<?php

namespace Database\Factories;

use App\Models\RoomCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    public function definition(): array
    {
        // Array of hotel room images from Unsplash
        $roomImages = [
            'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=600&q=80&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=600&q=80&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600&q=80&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=600&q=80&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600&q=80&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1578500494198-246f612d782b?w=600&q=80&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1605733881820-f1a3a9d5f7a6?w=600&q=80&auto=format&fit=crop',
        ];

        return [
            'room_category_id' => RoomCategory::factory(),
            'name' => 'Room '.$this->faker->unique()->numberBetween(100, 999),
            'description' => $this->faker->paragraph(2),
            'price_per_night' => $this->faker->randomFloat(2, 1200, 15000),
            'capacity' => $this->faker->numberBetween(1, 6),
            'is_available' => $this->faker->boolean(80),
            'image_path' => $this->faker->randomElement($roomImages),
        ];
    }
}

