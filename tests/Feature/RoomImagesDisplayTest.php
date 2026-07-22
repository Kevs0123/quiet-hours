<?php

use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays all available rooms with valid images on the home page', function () {
    $category = RoomCategory::factory()->create();

    // Create 15 rooms to test the expanded fallback images
    Room::factory()->count(15)->create([
        'room_category_id' => $category->id,
        'is_available' => true,
        'image_path' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80',
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();

    $rooms = $response->viewData('featuredRooms');
    expect($rooms)->toHaveCount(15);

    // Verify all rooms have image URLs
    $rooms->each(function ($room) {
        expect($room->image_url)->not->toBeNull();
        expect($room->image_url)->toContain('http');
    });

    // Check that all room names are visible
    $rooms->each(function ($room) use ($response) {
        $response->assertSee($room->name);
    });
});

it('falls back to placeholder images for rooms without image_path', function () {
    $category = RoomCategory::factory()->create();

    // Create 10 rooms without image_path
    Room::factory()->count(10)->create([
        'room_category_id' => $category->id,
        'is_available' => true,
        'image_path' => null,
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();

    $rooms = $response->viewData('featuredRooms');
    expect($rooms)->toHaveCount(10);

    // Verify all rooms have fallback images
    $rooms->each(function ($room) {
        expect($room->image_url)->not->toBeNull();
        // Should return placeholder since image_path is null
        expect($room->image_url)->toContain('placehold.co');
    });
});
