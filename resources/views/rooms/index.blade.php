@extends('layouts.app')
@section('title', 'Rooms')
@section('content')

<div class="card">
    <div class="page-header">
        <div>
            <h1>Rooms</h1>
            <p style="margin:0;">Browse and manage all rooms available at Quiet Hours.</p>
        </div>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-gold">+ New Room</a>
    </div>

    @if ($rooms->isNotEmpty())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Room Name</th>
                    <th>Category</th>
                    <th>Price / Night</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                <tr>
                    <td>
                        @if($room->image_path)
                        <a href="{{ route('admin.rooms.show', $room) }}">
                            <img src="{{ $room->image_url ?? 'https://via.placeholder.com/160x120?text=No+Photo' }}"
                                 onerror="this.onerror=null;this.src='https://via.placeholder.com/160x120?text=No+Photo'"
                                 alt="{{ $room->name }}"
                                 style="width:64px;height:48px;object-fit:cover;border-radius:6px;border:1px solid var(--cream2);display:block;">
                        </a>
                        @else
                        <img src="https://via.placeholder.com/160x120?text=No+Photo"
                             alt="No photo"
                             style="width:64px;height:48px;object-fit:cover;border-radius:6px;border:1px solid var(--cream2);display:block;filter:grayscale(.02);">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.rooms.show', $room) }}" style="font-weight:700;color:var(--navy);">{{ $room->name }}</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.show', $room->category) }}" style="font-size:13px;color:var(--gold2);text-decoration:none;">
                            {{ $room->category->name }}
                        </a>
                    </td>
                    <td style="font-weight:600;color:var(--navy);">₱{{ number_format($room->price_per_night, 2) }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:4px;font-size:13px;">
                            👥 {{ $room->capacity }} guest{{ $room->capacity !== 1 ? 's' : '' }}
                        </div>
                    </td>
                    <td>
                        @if ($room->is_available)
                            <span style="display:inline-flex;align-items:center;gap:4px;background:#d4edda;color:#155724;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;">
                                ● Available
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;background:#f8d7da;color:#721c24;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;">
                                ● Unavailable
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            <a class="btn btn-outline btn-sm" href="{{ route('admin.rooms.show', $room) }}" title="View details">View</a>
                            <a class="btn btn-outline btn-sm" href="{{ route('admin.rooms.edit', $room) }}" title="Edit this room">Edit</a>
                            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST"
                                  onsubmit="return confirm('Delete &quot;{{ $room->name }}&quot;? This cannot be undone.');"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete this room">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;">{{ $rooms->links() }}</div>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">🛏</div>
        <h3>No rooms yet</h3>
        <p style="margin-bottom:18px;">Start by adding your first room to the hotel.</p>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-gold">+ New Room</a>
    </div>
    @endif
</div>

<style>
    .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .actions form {
        display: inline;
    }
    
    table tbody tr:hover {
        background-color: #f9f8f6;
    }
</style>

@endsection
