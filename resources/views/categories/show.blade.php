@extends('layouts.app')
@section('title', $category->name)
@section('content')

<div class="card">
    <div class="page-header">
        <div>
            <h1>{{ $category->name }}</h1>
            <p style="margin:0;">{{ $category->description ?: 'No description provided.' }}</p>
        </div>
        <div class="actions">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline">Edit</a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">&larr; Back</a>
        </div>
    </div>

    <h3 style="margin-bottom:16px;">
        Rooms in this Category
        <span style="font-size:13px;font-weight:400;color:var(--muted);">({{ $category->rooms->count() }} total)</span>
    </h3>

    @if ($category->rooms->isNotEmpty())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price / Night</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($category->rooms as $room)
                <tr>
                    <td>
                        <a href="{{ route('admin.rooms.show', $room) }}">{{ $room->name }}</a>
                    </td>
                    <td>₱{{ number_format($room->price_per_night, 2) }}</td>
                    <td>{{ $room->capacity }} guest(s)</td>
                    <td>
                        @if ($room->is_available)
                            <span class="badge badge-available">Available</span>
                        @else
                            <span class="badge badge-unavailable">Unavailable</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-outline btn-sm">Edit</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state" style="padding:28px;">
        <p>No rooms assigned to this category yet.</p>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-gold btn-sm">+ Add a Room</a>
    </div>
    @endif
</div>

@endsection
