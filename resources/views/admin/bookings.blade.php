@extends('layouts.app')
@section('title', 'All Bookings')
@section('content')

<style>
    .status-tabs{display:flex;gap:8px;margin-bottom:18px;flex-wrap:wrap;}
    .status-tab{
        padding:8px 16px;border-radius:20px;font-size:13px;font-weight:600;
        text-decoration:none;color:var(--muted);background:var(--cream);
        border:1px solid var(--cream2);transition:background .2s,color .2s;
    }
    .status-tab:hover{background:var(--cream2);}
    .status-tab.active{background:var(--navy);color:#fff;border-color:var(--navy);}
    .bulk-bar{
        display:none;align-items:center;gap:12px;
        background:#fdf6ec;border:1px solid #ead7ab;border-radius:10px;
        padding:10px 16px;margin-bottom:14px;font-size:13px;color:#7a5c1e;
    }
    .bulk-bar.visible{display:flex;}
</style>

<div class="card">
    <div class="page-header">
        <div>
            <h1>All Bookings</h1>
            <p style="margin:0;">
                {{ $bookings->total() }} booking(s) on record.
                @php $pendingCount = \App\Models\Booking::where('status', \App\Models\Booking::STATUS_PENDING)->count(); @endphp
                @if($pendingCount)
                    <span class="badge badge-pending" style="margin-left:6px;">{{ $pendingCount }} awaiting confirmation</span>
                @endif
            </p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm">&larr; Dashboard</a>
    </div>

    <div class="status-tabs">
        <a href="{{ route('admin.bookings') }}" class="status-tab @if(!$status) active @endif">All</a>
        <a href="{{ route('admin.bookings', ['status' => 'pending_confirmation']) }}" class="status-tab @if($status === 'pending_confirmation') active @endif">Pending</a>
        <a href="{{ route('admin.bookings', ['status' => 'confirmed']) }}" class="status-tab @if($status === 'confirmed') active @endif">Confirmed</a>
        <a href="{{ route('admin.bookings', ['status' => 'rejected']) }}" class="status-tab @if($status === 'rejected') active @endif">Rejected</a>
    </div>

    <form action="{{ route('admin.bookings.notify-pending') }}" method="POST" style="margin-bottom:16px;">
        @csrf
        <button type="submit" class="btn btn-primary btn-sm">Notify All Pending Clients</button>
    </form>

    @if($bookings->isNotEmpty())
    <form id="bulk-form" action="{{ route('admin.bookings.bulk-confirm') }}" method="POST">
        @csrf

        <div class="bulk-bar" id="bulk-bar">
            <span id="bulk-count">0 selected</span>
            <button type="submit" class="btn btn-gold btn-sm" onclick="return confirm('Confirm all selected bookings? Guests will be emailed.');">
                ✓ Confirm Selected
            </button>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:34px;"><input type="checkbox" id="select-all"></th>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Account</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th style="text-align:center;">Persons</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>
                            @if($booking->isPending())
                                <input type="checkbox" name="booking_ids[]" value="{{ $booking->id }}" class="row-check">
                            @endif
                        </td>
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
                        <td><span class="badge {{ $booking->statusBadgeClass() }}">{{ $booking->statusLabel() }}</span></td>
                        <td style="font-size:12px;color:var(--muted);">{{ $booking->created_at->format('M j, Y') }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.bookings.show', $booking) }}"
                                   class="btn btn-outline btn-sm">View</a>
                                @if($booking->isPending())
                                    <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-gold btn-sm">Confirm</button>
                                    </form>
                                    <form action="{{ route('admin.bookings.notify', $booking) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm">Notify</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.bookings.edit', $booking) }}"
                                   class="btn btn-outline btn-sm">Edit</a>
                                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
                                      onsubmit="return confirm('Delete booking {{ $booking->booking_id }}?');" style="display:inline;">
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
    </form>
    <div style="margin-top:20px;">{{ $bookings->links() }}</div>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">📋</div>
        <h3>No bookings {{ $status ? 'with this status' : 'yet' }}</h3>
        <p>@if($status) Try a different filter above. @else Bookings will appear here once clients complete the booking wizard. @endif</p>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all');
    const rowChecks = document.querySelectorAll('.row-check');
    const bulkBar   = document.getElementById('bulk-bar');
    const bulkCount = document.getElementById('bulk-count');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-check:checked').length;
        bulkCount.textContent = checked + ' selected';
        bulkBar.classList.toggle('visible', checked > 0);
    }

    selectAll?.addEventListener('change', function () {
        rowChecks.forEach(cb => cb.checked = selectAll.checked);
        updateBulkBar();
    });

    rowChecks.forEach(cb => cb.addEventListener('change', updateBulkBar));
});
</script>

@endsection
