<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isClient();
    }

    public function update(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id || $user->isAdmin();
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id || $user->isAdmin();
    }
}
