<?php

use App\Models\Room;
use App\Models\RoomCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('keeps the existing room image when an empty upload is submitted', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);
    $category = RoomCategory::factory()->create();
    $room = Room::factory()->create([
        'room_category_id' => $category->id,
        'name' => 'Room 101',
        'image_path' => 'rooms/existing.jpg',
    ]);

    Storage::disk('public')->put($room->image_path, 'existing-image');

    $response = $this->actingAs($admin)->put(route('admin.rooms.update', $room), [
        'room_category_id' => $category->id,
        'name' => 'Updated Room',
        'description' => 'Updated description',
        'price_per_night' => 1200,
        'capacity' => 2,
        'is_available' => true,
        'image' => UploadedFile::fake()->create('', 0, 'image/jpeg'),
    ]);

    $response->assertRedirect(route('admin.rooms.index'));
    $room->refresh();
    expect($room->image_path)->toBe('rooms/existing.jpg');
});
