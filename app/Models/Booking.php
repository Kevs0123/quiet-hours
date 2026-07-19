<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_PENDING   = 'pending_confirmation';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REJECTED  = 'rejected';

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
        'payment_method',
        'payment_reference',
        'amount_paid',
        'status',
        'admin_notes',
        'confirmed_at',
        'confirmed_by',
    ];

    protected $casts = [
        'check_in_date'  => 'date',
        'check_out_date' => 'date',
        'confirmed_at'   => 'datetime',
        'amount_paid'    => 'decimal:2',
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

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_REJECTED  => 'Rejected',
            default                => 'Pending Confirmation',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_CONFIRMED => 'badge-available',
            self::STATUS_REJECTED  => 'badge-unavailable',
            default                => 'badge-pending',
        };
    }

    public function paymentMethodLabel(): string
    {
        return match ($this->payment_method) {
            'gcash'          => 'GCash',
            'paymaya'        => 'Maya',
            'bank_transfer'  => 'Bank Transfer',
            'credit_card'    => 'Credit / Debit Card',
            default          => $this->payment_method ? ucfirst(str_replace('_', ' ', $this->payment_method)) : '—',
        };
    }
}
