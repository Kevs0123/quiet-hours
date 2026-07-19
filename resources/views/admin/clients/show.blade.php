@extends('layouts.app')
@section('title', $client->name)
@section('content')

<div style="max-width:860px;margin:0 auto;">

    {{-- Client info card --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="page-header">
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--navy);
                            color:#fff;display:flex;align-items:center;justify-content:center;
                            font-size:22px;font-family:'Playfair Display',serif;font-weight:700;flex-shrink:0;">
                    {{ strtoupper(substr($client->name,0,1)) }}
                </div>
                <div>
                    <h1 style="margin-bottom:2px;">{{ $client->name }}</h1>
                    <div style="font-size:13px;color:var(--muted);">{{ $client->email }}</div>
                </div>
            </div>
            <div class="actions">
                <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-gold btn-sm">Edit</a>
                <a href="{{ route('admin.clients.index') }}" class="btn btn-outline btn-sm">&larr; Back</a>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-top:8px;">
            <div style="text-align:center;background:var(--cream);border-radius:10px;padding:16px;">
                <div style="font-size:24px;font-weight:700;color:var(--navy);font-family:'Playfair Display',serif;">
                    {{ $client->bookings->count() }}
                </div>
                <div style="font-size:12px;color:var(--muted);margin-top:2px;">Total Bookings</div>
            </div>
            <div style="text-align:center;background:var(--cream);border-radius:10px;padding:16px;">
                <div style="font-size:14px;font-weight:600;color:var(--navy);">
                    {{ $client->created_at->format('M j, Y') }}
                </div>
                <div style="font-size:12px;color:var(--muted);margin-top:2px;">Member Since</div>
            </div>
            <div style="text-align:center;background:var(--cream);border-radius:10px;padding:16px;">
                <span class="badge badge-available">Client</span>
                <div style="font-size:12px;color:var(--muted);margin-top:6px;">Role</div>
            </div>
        </div>
    </div>

    {{-- Bookings table --}}
    <div class="card">
        <div class="page-header" style="margin-bottom:18px;">
            <h2 style="margin:0;">Booking History</h2>
        </div>

        @if($client->bookings->isNotEmpty())
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Nights</th>
                        <th>Persons</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($client->bookings as $booking)
                    <tr>
                        <td>
                            <code style="background:var(--cream);padding:2px 7px;border-radius:4px;
                                         font-size:12px;font-weight:700;">
                                {{ $booking->booking_id }}
                            </code>
                        </td>
                        <td style="font-size:13px;">{{ $booking->event_name }}</td>
                        <td style="font-size:13px;">{{ $booking->check_in_date?->format('M j, Y') ?? '—' }}</td>
                        <td style="font-size:13px;">{{ $booking->check_out_date?->format('M j, Y') ?? '—' }}</td>
                        <td style="font-size:13px;">
                            {{ $booking->check_in_date && $booking->check_out_date
                                ? $booking->check_in_date->diffInDays($booking->check_out_date)
                                : '—' }}
                        </td>
                        <td>{{ $booking->number_of_persons }}</td>
                        <td><span class="badge {{ $booking->statusBadgeClass() }}">{{ $booking->statusLabel() }}</span></td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.bookings.show', $booking) }}"
                                   class="btn btn-outline btn-sm">View</a>
                                <a href="{{ route('admin.bookings.edit', $booking) }}"
                                   class="btn btn-outline btn-sm">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state" style="padding:28px;">
            <p>This client has no bookings yet.</p>
        </div>
        @endif
    </div>

</div>

@endsection
