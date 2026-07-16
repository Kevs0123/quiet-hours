<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_category_id',
        'name',
        'description',
        'image_path',
        'price_per_night',
        'capacity',
        'is_available',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Public URL for this room's photo, or a neutral placeholder if none
     * has been uploaded yet.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->image_path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->image_path);
        }

        return 'https://placehold.co/600x400/ede9df/6b6860?text=No+Photo';
    }

    /**
     * A room belongs to one category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }
}
