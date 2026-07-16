<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'customer_name',
        'booking_id',
        'event_name',
        'check_in_date',
        'check_out_date',
        'number_of_persons',
        'confirmation_file_path',
        'confirmation_file_type',
    ];

    protected $casts = [
        'check_in_date'  => 'date',
        'check_out_date' => 'date',
    ];

    public function getNightsAttribute(): int
    {
        return (int) $this->check_in_date->diffInDays($this->check_out_date);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
