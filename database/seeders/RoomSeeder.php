<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        RoomCategory::all()->each(function (RoomCategory $category) {
            Room::factory()->count(4)->create([
                'room_category_id' => $category->id,
            ]);
        });
    }
}
