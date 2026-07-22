<?php

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('colors calendar events by booking status', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Booking::create([
        'user_id' => $admin->id,
        'customer_name' => 'Pending Customer',
        'booking_id' => 'BK-PENDING-001',
        'event_name' => 'Pending Stay',
        'check_in_date' => now()->toDateString(),
        'check_out_date' => now()->addDay()->toDateString(),
        'number_of_persons' => 2,
        'confirmation_file_path' => 'pending.pdf',
        'confirmation_file_type' => 'pdf',
        'status' => Booking::STATUS_PENDING,
    ]);

    Booking::create([
        'user_id' => $admin->id,
        'customer_name' => 'Confirmed Customer',
        'booking_id' => 'BK-CONFIRMED-001',
        'event_name' => 'Confirmed Stay',
        'check_in_date' => now()->addDays(2)->toDateString(),
        'check_out_date' => now()->addDays(3)->toDateString(),
        'number_of_persons' => 2,
        'confirmation_file_path' => 'confirmed.pdf',
        'confirmation_file_type' => 'pdf',
        'status' => Booking::STATUS_CONFIRMED,
    ]);

    Booking::create([
        'user_id' => $admin->id,
        'customer_name' => 'Rejected Customer',
        'booking_id' => 'BK-REJECTED-001',
        'event_name' => 'Rejected Stay',
        'check_in_date' => now()->addDays(4)->toDateString(),
        'check_out_date' => now()->addDays(5)->toDateString(),
        'number_of_persons' => 2,
        'confirmation_file_path' => 'rejected.pdf',
        'confirmation_file_type' => 'pdf',
        'status' => Booking::STATUS_REJECTED,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();

    $events = json_decode($response->viewData('calendarEvents'), true);
    $pendingEvent = collect($events)->firstWhere(fn ($event) => str_contains($event['title'], 'Pending Customer'));
    $confirmedEvent = collect($events)->firstWhere(fn ($event) => str_contains($event['title'], 'Confirmed Customer'));
    $rejectedEvent = collect($events)->firstWhere(fn ($event) => str_contains($event['title'], 'Rejected Customer'));

    expect($pendingEvent['color'])->toBe('#f59e0b');
    expect($confirmedEvent['color'])->toBe('#22c55e');
    expect($rejectedEvent['color'])->toBe('#ef4444');
});
