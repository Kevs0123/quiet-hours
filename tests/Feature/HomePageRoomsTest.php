<?php

use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays all available rooms on the home page', function () {
    $category = RoomCategory::factory()->create(['name' => 'Deluxe Room']);

    Room::factory()->count(10)->create([
        'room_category_id' => $category->id,
        'is_available' => true,
    ]);

    Room::factory()->count(3)->create([
        'room_category_id' => $category->id,
        'is_available' => false,
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $allRooms = $response->viewData('featuredRooms');

    // Should show 10 available rooms (not the 3 unavailable ones)
    expect($allRooms)->toHaveCount(10);

    // All displayed rooms should be available
    $allRooms->each(fn ($room) => expect($room->is_available)->toBeTrue());

    // Check that the rooms are rendered in the HTML
    $allRooms->each(function ($room) use ($response) {
        $response->assertSee($room->name);
    });
});

it('displays room images on the home page', function () {
    $category = RoomCategory::factory()->create();

    Room::factory()->count(5)->create([
        'room_category_id' => $category->id,
        'is_available' => true,
        'image_path' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80',
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();

    $rooms = $response->viewData('featuredRooms');

    expect($rooms)->not->toBeEmpty();

    $rooms->each(function ($room) use ($response) {
        // Verify image URL is accessible and displayed
        $response->assertSee($room->image_url);
    });
});
