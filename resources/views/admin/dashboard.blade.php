@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')

<style>
    .dashboard-header {
        margin-bottom: 32px;
    }
    .dashboard-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        color: var(--navy);
        margin-bottom: 6px;
    }
    .dashboard-header p {
        color: var(--muted);
        font-size: 14px;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(27, 42, 74, 0.06);
        border-top: 4px solid var(--gold);
        transition: transform .2s, box-shadow .2s;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(27, 42, 74, 0.12);
    }

    .stat-card.bookings { border-top-color: var(--gold); }
    .stat-card.dates { border-top-color: var(--navy); }
    .stat-card.users { border-top-color: #6c757d; }
    .stat-card.rooms { border-top-color: #22c55e; }
    .stat-card.categories { border-top-color: #f59e0b; }
    .stat-card.clients { border-top-color: #06b6d4; }

    .stat-icon {
        font-size: 40px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 40px;
        font-weight: 700;
        color: var(--navy);
    }

    .stat-label {
        font-size: 13px;
        color: var(--muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-link {
        font-size: 12px;
        color: var(--gold2);
        text-decoration: none;
        font-weight: 700;
        transition: color .2s;
        align-self: center;
    }

    .stat-link:hover {
        color: var(--gold);
    }

    .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 12px;
        font-size: 12px;
        color: var(--muted);
    }

    .calendar-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
    }

    .calendar-legend-swatch {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .actions-bar {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 32px;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
        }
        .stat-card {
            padding: 18px;
        }
        .stat-number {
            font-size: 32px;
        }
    }
</style>

<div class="dashboard-header">
    <h1>Admin Dashboard</h1>
    <p>Welcome back, Admin. Here's your hotel overview.</p>
</div>

{{-- Stats Grid --}}
<div class="stats-grid">
    <div class="stat-card bookings">
        <div class="stat-icon">📋</div>
        <div class="stat-number" id="stat-totalBookings">{{ $totalBookings }}</div>
        <div class="stat-label">Total Bookings</div>
        <a href="{{ route('admin.bookings') }}" class="stat-link">View all →</a>
    </div>

    <div class="stat-card dates">
        <div class="stat-icon">📅</div>
        <div class="stat-number" id="stat-totalBookedDates">{{ $totalBookedDates }}</div>
        <div class="stat-label">Total Booked Dates</div>
    </div>

    <div class="stat-card users">
        <div class="stat-icon">👥</div>
        <div class="stat-number" id="stat-totalUsers">{{ $totalUsers }}</div>
        <div class="stat-label">Total Users</div>
        <a href="{{ route('admin.clients.index') }}" class="stat-link">Manage →</a>
    </div>

    <div class="stat-card rooms">
        <div class="stat-icon">🛏</div>
        <div class="stat-number" id="stat-totalRooms">{{ $totalRooms }}</div>
        <div class="stat-label">Total Rooms</div>
        <a href="{{ route('admin.rooms.index') }}" class="stat-link">Manage →</a>
    </div>

    <div class="stat-card categories">
        <div class="stat-icon">🗂</div>
        <div class="stat-number" id="stat-totalCategories">{{ $totalCategories }}</div>
        <div class="stat-label">Room Categories</div>
        <a href="{{ route('admin.categories.index') }}" class="stat-link">Manage →</a>
    </div>

    <div class="stat-card clients">
        <div class="stat-icon">🧑‍💼</div>
        <div class="stat-number" id="stat-totalClients">{{ $totalClients }}</div>
        <div class="stat-label">Registered Clients</div>
        <a href="{{ route('admin.clients.index') }}" class="stat-link">Manage →</a>
    </div>
</div>

{{-- Quick Actions Bar --}}
<div class="actions-bar">
    <a href="{{ route('admin.clients.create') }}" class="btn btn-gold">+ New Client</a>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ New Category</a>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">+ New Room</a>
    <a href="{{ route('admin.clients.index') }}" class="btn btn-outline">👥 All Clients</a>
    <a href="{{ route('admin.bookings') }}" class="btn btn-outline">📋 All Bookings</a>
</div>

{{-- Event Calendar --}}
<div class="card" style="margin-bottom:32px;">
    <div class="page-header" style="margin-bottom:20px;">
        <div>
            <h2 style="margin:0;">Booking Calendar</h2>
            <p style="margin:0;font-size:12px;color:var(--muted);margin-top:6px;">
                Shows all booked dates with room categories.
            </p>
            <div class="calendar-legend">
                <span class="calendar-legend-item"><span class="calendar-legend-swatch" style="background:#f59e0b;"></span>Pending</span>
                <span class="calendar-legend-item"><span class="calendar-legend-swatch" style="background:#22c55e;"></span>Confirmed</span>
                <span class="calendar-legend-item"><span class="calendar-legend-swatch" style="background:#ef4444;"></span>Rejected</span>
            </div>
        </div>
    </div>

    <div id="calendar-container" style="background:#f8f9fa;border-radius:8px;padding:20px;overflow-x:auto;">
        <div id="calendar" style="min-width:100%;"></div>
    </div>
</div>

{{-- Recent bookings --}}
<div class="card">
    <div class="page-header" style="margin-bottom:20px;">
        <h2 style="margin:0;">Recent Bookings</h2>
        <a href="{{ route('admin.bookings') }}" class="btn btn-outline btn-sm">See all</a>
    </div>

    @if($recentBookings->isNotEmpty())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Persons</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentBookings as $booking)
                <tr>
                    <td>
                        <code style="background:var(--cream);padding:2px 7px;border-radius:4px;font-size:12px;">
                            {{ $booking->booking_id }}
                        </code>
                    </td>
                    <td>{{ $booking->customer_name }}</td>
                    <td style="font-size:13px;">{{ $booking->event_name }}</td>
                    <td style="font-size:13px;">{{ $booking->check_in_date?->format('M j, Y') ?? '—' }}</td>
                    <td style="font-size:13px;">{{ $booking->check_out_date?->format('M j, Y') ?? '—' }}</td>
                    <td>{{ $booking->number_of_persons }}</td>
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
        <div class="empty-state-icon">📋</div>
        <p>No bookings yet.</p>
    </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.21/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.21/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ---- Booking calendar (FullCalendar) ----
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
        eventClick: function (info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        },
    });
    calendar.render();

    // ---- Live stat polling (updates numbers without a full page reload) ----
    const statIds = ['totalBookings', 'totalBookedDates', 'totalUsers', 'totalRooms', 'totalCategories', 'totalClients'];

    function refreshStats() {
        fetch('{{ route('admin.dashboard.stats') }}', { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                statIds.forEach(function (key) {
                    const el = document.getElementById('stat-' + key);
                    if (el && data[key] !== undefined && el.textContent != data[key]) {
                        el.textContent = data[key];
                        el.style.transition = 'color .3s';
                        el.style.color = 'var(--gold2)';
                        setTimeout(() => { el.style.color = ''; }, 600);
                    }
                });
            })
            .catch(() => { /* silent — next poll will try again */ });
    }

    // Poll every 15s, and immediately whenever the tab regains focus
    setInterval(refreshStats, 15000);
    window.addEventListener('focus', refreshStats);
});
</script>

@endsection
