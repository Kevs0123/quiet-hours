@extends('layouts.app')
@section('title', 'All Bookings')
@section('content')

<div class="card">
    <div class="page-header">
        <div>
            <h1>All Bookings</h1>
            <p style="margin:0;">{{ $bookings->total() }} booking(s) on record.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm">&larr; Dashboard</a>
    </div>

    @if($bookings->isNotEmpty())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Account</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th style="text-align:center;">Persons</th>
                    <th>File</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>
                        <code style="background:var(--cream);padding:2px 7px;border-radius:4px;font-size:12px;font-weight:700;">
                            {{ $booking->booking_id }}
                        </code>
                    </td>
                    <td style="font-weight:600;">{{ $booking->customer_name }}</td>
                    <td style="font-size:12px;color:var(--muted);">{{ $booking->user?->email ?? '—' }}</td>
                    <td style="font-size:13px;">{{ $booking->event_name }}</td>
                    <td style="font-size:13px;">{{ $booking->check_in_date?->format('M j, Y') ?? '—' }}</td>
                    <td style="font-size:13px;">{{ $booking->check_out_date?->format('M j, Y') ?? '—' }}</td>
                    <td style="text-align:center;">{{ $booking->number_of_persons }}</td>
                    <td>
                        @if(in_array($booking->confirmation_file_type, ['jpg','jpeg','png']))
                            <a href="{{ asset('storage/'.$booking->confirmation_file_path) }}"
                               target="_blank" style="font-size:12px;">🖼 Image</a>
                        @else
                            <a href="{{ asset('storage/'.$booking->confirmation_file_path) }}"
                               target="_blank" style="font-size:12px;">📄 PDF</a>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--muted);">{{ $booking->created_at->format('M j, Y') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                               class="btn btn-outline btn-sm">View</a>
                            <a href="{{ route('admin.bookings.edit', $booking) }}"
                               class="btn btn-outline btn-sm">Edit</a>
                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
                                  onsubmit="return confirm('Delete booking {{ $booking->booking_id }}?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;">{{ $bookings->links() }}</div>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">📋</div>
        <h3>No bookings yet</h3>
        <p>Bookings will appear here once clients complete the booking wizard.</p>
    </div>
    @endif
</div>

@endsection
