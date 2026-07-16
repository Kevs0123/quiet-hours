<?php

namespace App\Console\Commands;

use App\Models\Room;
use Illuminate\Console\Command;

class AddRoomImages extends Command
{
    protected $signature = 'rooms:add-images';
    protected $description = 'Add images to rooms that don\'t have them';

    public function handle()
    {
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

        $updated = 0;
        $roomsWithoutImages = Room::whereNull('image_path')->get();

        foreach ($roomsWithoutImages as $room) {
            $room->update([
                'image_path' => $roomImages[array_rand($roomImages)],
            ]);
            $updated++;
        }

        if ($updated > 0) {
            $this->info("✓ Updated {$updated} room(s) with images.");
        } else {
            $this->info("All rooms already have images.");
        }
    }
}
