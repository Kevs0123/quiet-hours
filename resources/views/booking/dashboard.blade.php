@extends('layouts.app')
@section('title', 'My Dashboard')
@section('content')

<div style="margin-bottom:8px;">
    <h1>My Dashboard</h1>
    <p>Welcome back, {{ auth()->user()->name }}. Here's your booking overview.</p>
</div>

{{-- Stats grid --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:18px;margin-bottom:32px;">
    <div class="card card-sm" style="text-align:center;border-top:4px solid var(--gold);">
        <div style="font-size:34px;margin-bottom:6px;">📋</div>
        <div style="font-size:32px;font-weight:700;color:var(--navy);font-family:'Playfair Display',serif;">{{ $myTotalBookings }}</div>
        <div style="font-size:13px;color:var(--muted);margin-top:2px;">My Total Bookings</div>
        <a href="{{ route('booking.history') }}" style="font-size:12px;color:var(--gold2);text-decoration:none;font-weight:600;">View history →</a>
    </div>
    <div class="card card-sm" style="text-align:center;border-top:4px solid var(--success);">
        <div style="font-size:34px;margin-bottom:6px;">🌙</div>
        <div style="font-size:32px;font-weight:700;color:var(--navy);font-family:'Playfair Display',serif;">{{ $myTotalNights }}</div>
        <div style="font-size:13px;color:var(--muted);margin-top:2px;">My Total Nights Booked</div>
    </div>
    <div class="card card-sm" style="text-align:center;border-top:4px solid var(--navy);">
        <div style="font-size:34px;margin-bottom:6px;">📅</div>
        <div style="font-size:32px;font-weight:700;color:var(--navy);font-family:'Playfair Display',serif;">{{ $totalBookedDatesSystemWide }}</div>
        <div style="font-size:13px;color:var(--muted);margin-top:2px;">Total Booked Dates (all guests)</div>
    </div>
    <div class="card card-sm" style="text-align:center;border-top:4px solid #17a2b8;">
        <div style="font-size:34px;margin-bottom:6px;">👥</div>
        <div style="font-size:32px;font-weight:700;color:var(--navy);font-family:'Playfair Display',serif;">{{ $totalUsers }}</div>
        <div style="font-size:13px;color:var(--muted);margin-top:2px;">Total Registered Users</div>
    </div>
</div>

{{-- Upcoming booking --}}
<div class="card" style="margin-bottom:32px;">
    <h2 style="margin-bottom:16px;">Your Next Stay</h2>
    @if($upcomingBooking)
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:14px;">
            <div><span style="color:var(--muted);">Booking ID:</span>
                <code style="background:var(--cream);padding:2px 8px;border-radius:4px;font-weight:700;letter-spacing:1px;">{{ $upcomingBooking->booking_id }}</code>
            </div>
            <div><span style="color:var(--muted);">Room:</span> <strong>{{ $upcomingBooking->room->name ?? $upcomingBooking->event_name }}</strong></div>
            <div><span style="color:var(--muted);">Check-in:</span> <strong>{{ $upcomingBooking->check_in_date->format('M j, Y') }}</strong></div>
            <div><span style="color:var(--muted);">Check-out:</span> <strong>{{ $upcomingBooking->check_out_date->format('M j, Y') }}</strong></div>
        </div>
    @else
        <div class="empty-state" style="padding:20px;">
            <div class="empty-state-icon">🛏</div>
            <p>No upcoming bookings yet.</p>
            <a href="{{ route('booking.home') }}" class="btn btn-gold" style="margin-top:12px;">Book a Room →</a>
        </div>
    @endif
</div>

{{-- Booking calendar (read-only, privacy-safe) --}}
<div class="card">
    <div class="page-header" style="margin-bottom:20px;">
        <div>
            <h2 style="margin:0;">Booking Calendar</h2>
            <p style="margin:0;font-size:12px;color:var(--muted);margin-top:6px;">
                <span style="background:#1a6b3a;color:#fff;padding:2px 6px;border-radius:3px;font-size:11px;font-weight:600;">📍 Your stays</span>
                &nbsp;
                <span style="background:#c9a86a;color:#fff;padding:2px 6px;border-radius:3px;font-size:11px;font-weight:600;">🔒 Booked by others</span>
            </p>
        </div>
    </div>

    <div id="calendar-container" style="background:#f8f9fa;border-radius:8px;padding:20px;overflow-x:auto;">
        <div id="calendar" style="min-width:100%;"></div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.21/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.21/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const events = {!! $calendarEvents !!};

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth',
        },
        events: events,
        eventDisplay: 'block',
    });
    calendar.render();
});
</script>

@endsection
