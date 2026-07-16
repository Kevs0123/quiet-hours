@extends('layouts.app')
@section('title', $room->name)
@section('content')

<div class="card" style="max-width:640px;">
    <div class="page-header">
        <h1>{{ $room->name }}</h1>
        <div class="actions">
            <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-gold btn-sm">Edit</a>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline btn-sm">&larr; Back</a>
        </div>
    </div>

        <img src="{{ $room->image_url ?? 'https://via.placeholder.com/1200x680?text=No+Photo' }}"
             onerror="this.onerror=null;this.src='https://via.placeholder.com/1200x680?text=No+Photo'"
         style="width:100%;max-height:280px;object-fit:cover;border-radius:10px;margin-bottom:20px;border:1px solid var(--cream2);">

    <table class="detail-list">
        <tr>
            <th>Category</th>
            <td>
                <a href="{{ route('admin.categories.show', $room->category) }}">{{ $room->category->name }}</a>
            </td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $room->description ?: '—' }}</td>
        </tr>
        <tr>
            <th>Price per Night</th>
            <td><strong style="font-size:17px;color:var(--navy);">₱{{ number_format($room->price_per_night, 2) }}</strong></td>
        </tr>
        <tr>
            <th>Capacity</th>
            <td>{{ $room->capacity }} guest(s)</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if ($room->is_available)
                    <span class="badge badge-available">Available</span>
                @else
                    <span class="badge badge-unavailable">Unavailable</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Created</th>
            <td style="color:var(--muted);font-size:13px;">{{ $room->created_at->format('F j, Y') }}</td>
        </tr>
    </table>

    <hr>

    <div class="actions" style="margin-top:4px;">
        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST"
              onsubmit="return confirm('Delete &quot;{{ $room->name }}&quot;? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">🗑 Delete Room</button>
        </form>
    </div>
</div>

@endsection
