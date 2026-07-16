@extends('layouts.app')
@section('title', 'Booking ' . $booking->booking_id)
@section('content')

@php
    $nights  = $booking->check_in_date && $booking->check_out_date
                ? $booking->check_in_date->diffInDays($booking->check_out_date) : 0;
    $fileUrl = asset('storage/' . $booking->confirmation_file_path);
    $isPdf   = strtolower($booking->confirmation_file_type) === 'pdf';
    $isImage = in_array(strtolower($booking->confirmation_file_type), ['jpg','jpeg','png']);
@endphp

<div style="max-width:860px;margin:0 auto;">

    {{-- Header --}}
    <div class="page-header" style="margin-bottom:20px;">
        <div>
            <h1 style="margin-bottom:4px;">Booking Details</h1>
            <code style="background:var(--navy);color:#fff;padding:3px 12px;border-radius:6px;
                         font-size:15px;letter-spacing:2px;font-weight:700;">
                {{ $booking->booking_id }}
            </code>
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

        {{-- Right: file --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:13px 18px;background:var(--cream);border-bottom:1px solid var(--cream2);
                        font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--navy);">
                📎 Confirmation File
            </div>
            <div style="padding:24px;text-align:center;">
                @if($isPdf)
                    <div style="width:72px;height:72px;background:linear-gradient(135deg,#ff5252,#c62828);
                                border-radius:14px;display:flex;align-items:center;justify-content:center;
                                margin:0 auto 14px;box-shadow:0 6px 20px rgba(198,40,40,.28);">
                        <svg width="34" height="34" fill="#fff" viewBox="0 0 24 24">
                            <path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/>
                        </svg>
                    </div>
                    <div style="display:inline-flex;align-items:center;gap:5px;background:#fde9e7;
                                color:#b3261e;font-size:11px;font-weight:700;padding:4px 12px;
                                border-radius:20px;margin-bottom:12px;">📄 PDF File</div>
                    <div style="font-size:13px;font-weight:700;color:var(--navy);word-break:break-all;margin-bottom:16px;">
                        {{ basename($booking->confirmation_file_path) }}
                    </div>
                    <a href="{{ $fileUrl }}" target="_blank"
                       style="display:flex;align-items:center;justify-content:center;gap:8px;
                              padding:11px 18px;background:#c62828;color:#fff;font-size:14px;
                              font-weight:700;border-radius:8px;text-decoration:none;margin-bottom:8px;">
                        ⬇ Download PDF
                    </a>
                    <a href="{{ $fileUrl }}" target="_blank"
                       style="display:flex;align-items:center;justify-content:center;gap:8px;
                              padding:11px 18px;background:var(--cream);color:var(--navy);font-size:14px;
                              font-weight:600;border-radius:8px;text-decoration:none;
                              border:1.5px solid var(--cream2);">
                        🔍 Open in Browser
                    </a>

                @elseif($isImage)
                    <div style="border-radius:10px;overflow:hidden;border:1px solid var(--cream2);
                                margin-bottom:14px;box-shadow:0 2px 12px rgba(0,0,0,.08);">
                        <img src="{{ $fileUrl }}" alt="Confirmation"
                             style="width:100%;display:block;max-height:220px;object-fit:cover;">
                    </div>
                    <a href="{{ $fileUrl }}" target="_blank"
                       style="display:flex;align-items:center;justify-content:center;gap:8px;
                              padding:11px 18px;background:var(--navy);color:#fff;font-size:14px;
                              font-weight:700;border-radius:8px;text-decoration:none;margin-bottom:8px;">
                        🔍 View Full Image
                    </a>
                    <a href="{{ $fileUrl }}" download
                       style="display:flex;align-items:center;justify-content:center;gap:8px;
                              padding:11px 18px;background:var(--cream);color:var(--navy);font-size:14px;
                              font-weight:600;border-radius:8px;text-decoration:none;
                              border:1.5px solid var(--cream2);">
                        ⬇ Download Image
                    </a>
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
