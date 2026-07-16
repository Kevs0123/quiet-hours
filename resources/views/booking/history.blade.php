@extends('layouts.app')
@section('title', 'Booking History')
@section('content')

<div style="max-width:920px;margin:0 auto;">
    <div class="card" style="margin-bottom:24px;">
        <div class="page-header" style="display:flex;justify-content:space-between;align-items:center;gap:14px;">
            <div>
                <h1 style="margin:0;">Booking History</h1>
                <p style="margin:6px 0 0;color:var(--muted);">Review all of your completed bookings in one place.</p>
            </div>
            <a href="{{ route('booking.home') }}" class="btn btn-outline">New Booking</a>
        </div>
    </div>

    <div class="card">
        @if($bookings->isNotEmpty())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Room</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Nights</th>
                            <th>Guests</th>
                            <th>Confirmation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td><code style="background:var(--cream);padding:4px 8px;border-radius:6px;font-size:12px;font-weight:700;">{{ $booking->booking_id }}</code></td>
                                <td>{{ $booking->event_name }}</td>
                                <td>{{ $booking->check_in_date?->format('M j, Y') ?? '—' }}</td>
                                <td>{{ $booking->check_out_date?->format('M j, Y') ?? '—' }}</td>
                                <td>{{ $booking->nights }}</td>
                                <td>{{ $booking->number_of_persons }}</td>
                                <td style="font-size:13px;">{{ ucfirst($booking->confirmation_file_type) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state" style="padding:42px 20px;text-align:center;">
                <div class="empty-state-icon">📭</div>
                <p style="margin:0;font-size:15px;color:var(--muted);">You have not completed any bookings yet.</p>
            </div>
        @endif
    </div>
</div>

@endsection