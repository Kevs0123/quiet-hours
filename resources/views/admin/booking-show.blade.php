@extends('layouts.app')
@section('title', 'Booking ' . $booking->booking_id)
@section('content')

@php
    $nights = $booking->check_in_date && $booking->check_out_date
                ? $booking->check_in_date->diffInDays($booking->check_out_date) : 0;
@endphp

<div style="max-width:860px;margin:0 auto;">

    {{-- Header --}}
    <div class="page-header" style="margin-bottom:20px;">
        <div>
            <h1 style="margin-bottom:4px;">Booking Details</h1>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <code style="background:var(--navy);color:#fff;padding:3px 12px;border-radius:6px;
                             font-size:15px;letter-spacing:2px;font-weight:700;">
                    {{ $booking->booking_id }}
                </code>
                <span class="badge {{ $booking->statusBadgeClass() }}">{{ $booking->statusLabel() }}</span>
            </div>
        </div>
        <div class="actions">
            <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-gold btn-sm">✏ Edit</a>
            <a href="{{ route('admin.bookings') }}" class="btn btn-outline btn-sm">&larr; Back</a>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

        {{-- Left: details --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:13px 18px;background:var(--cream);border-bottom:1px solid var(--cream2);
                        font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--navy);">
                📋 Booking Information
            </div>
            @php
            $rows = [
                ['👤','Customer',    $booking->customer_name],
                ['📧','Account',     $booking->user?->email ?? '—'],
                ['🛏','Room',        $booking->event_name],
                ['🗂','Category',    $booking->room?->category?->name ?? '—'],
                ['📅','Check-in',    $booking->check_in_date?->format('F j, Y') ?? '—'],
                ['📅','Check-out',   $booking->check_out_date?->format('F j, Y') ?? '—'],
                ['🌙','Nights',      $nights . ($nights==1?' night':' nights')],
                ['👥','Persons',     $booking->number_of_persons],
                ['📆','Booked On',   $booking->created_at->format('F j, Y g:i A')],
            ];
            @endphp
            @foreach($rows as $row)
            <div style="display:flex;gap:10px;padding:12px 18px;border-bottom:1px solid var(--cream2);font-size:14px;">
                <span style="font-size:15px;width:20px;text-align:center;flex-shrink:0;">{{ $row[0] }}</span>
                <span style="color:var(--muted);min-width:100px;font-size:13px;">{{ $row[1] }}</span>
                <span style="color:var(--navy);font-weight:600;">{{ $row[2] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Right: payment + confirmation actions --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:13px 18px;background:var(--cream);border-bottom:1px solid var(--cream2);
                        font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--navy);">
                💳 Payment &amp; Confirmation
            </div>

            @php
            $payRows = [
                ['💳','Method',      $booking->paymentMethodLabel()],
                ['🔖','Reference',   $booking->payment_reference ?? '—'],
                ['💰','Amount',      $booking->amount_paid ? '₱'.number_format($booking->amount_paid, 2) : '—'],
            ];
            @endphp
            @foreach($payRows as $row)
            <div style="display:flex;gap:10px;padding:12px 18px;border-bottom:1px solid var(--cream2);font-size:14px;">
                <span style="font-size:15px;width:20px;text-align:center;flex-shrink:0;">{{ $row[0] }}</span>
                <span style="color:var(--muted);min-width:100px;font-size:13px;">{{ $row[1] }}</span>
                <span style="color:var(--navy);font-weight:600;">{{ $row[2] }}</span>
            </div>
            @endforeach

            <div style="padding:20px 18px;">
                @if($booking->isConfirmed())
                    <div style="text-align:center;padding:10px 0;">
                        <div style="font-size:32px;margin-bottom:8px;">✅</div>
                        <div style="font-weight:700;color:var(--navy);margin-bottom:4px;">Confirmed</div>
                        <div style="font-size:12px;color:var(--muted);">
                            by {{ $booking->confirmedBy?->name ?? 'admin' }} on {{ $booking->confirmed_at?->format('M j, Y g:i A') }}
                        </div>
                    </div>
                @elseif($booking->isRejected())
                    <div style="text-align:center;padding:10px 0;">
                        <div style="font-size:32px;margin-bottom:8px;">⚠️</div>
                        <div style="font-weight:700;color:var(--navy);margin-bottom:8px;">Rejected</div>
                        <div style="background:var(--cream);border-radius:8px;padding:10px 12px;font-size:13px;text-align:left;color:var(--ink);">
                            {{ $booking->admin_notes }}
                        </div>
                    </div>
                @else
                    <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" style="margin-bottom:10px;">
                        @csrf
                        <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;">
                            ✓ Confirm Booking
                        </button>
                    </form>
                    <details>
                        <summary style="cursor:pointer;font-size:13px;color:var(--danger);font-weight:600;">
                            Reject this booking instead
                        </summary>
                        <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" style="margin-top:10px;">
                            @csrf
                            <div class="field" style="margin-bottom:10px;">
                                <label>Reason for rejection</label>
                                <textarea name="admin_notes" rows="3" placeholder="e.g. Payment reference could not be verified." required></textarea>
                                @error('admin_notes')<span class="error-text">{{ $message }}</span>@enderror
                            </div>
                            <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">
                                ✕ Reject Booking
                            </button>
                        </form>
                    </details>
                @endif
            </div>
        </div>

    </div>

    {{-- Delete --}}
    <div style="margin-top:20px;">
        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
              onsubmit="return confirm('Permanently delete booking {{ $booking->booking_id }}?');">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">🗑 Delete This Booking</button>
        </form>
    </div>

</div>
@endsection
