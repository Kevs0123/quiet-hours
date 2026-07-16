@extends('layouts.app')
@section('title', 'Room Categories')
@section('content')

<div class="card">
    <div class="page-header">
        <div>
            <h1>Room Categories</h1>
            <p style="margin:0;">Manage all room types offered at Quiet Hours Hotel.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-gold">+ New Category</a>
    </div>

    @if ($categories->isNotEmpty())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th style="text-align:center;"># Rooms</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                <tr>
                    <td style="color:var(--muted);font-size:13px;">{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('admin.categories.show', $category) }}" style="font-weight:600;">
                            {{ $category->name }}
                        </a>
                        <div style="font-size:11px;color:var(--muted);margin-top:2px;">
                            /{{ $category->slug }}
                        </div>
                    </td>
                    <td style="color:var(--muted);">{{ Str::limit($category->description, 65) ?: '—' }}</td>
                    <td style="text-align:center;">
                        <span class="badge" style="background:var(--cream);color:var(--navy);">
                            {{ $category->rooms_count }}
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            <a class="btn btn-outline btn-sm" href="{{ route('admin.categories.show', $category) }}">View</a>
                            <a class="btn btn-outline btn-sm" href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                  onsubmit="return confirm('Delete &quot;{{ $category->name }}&quot;? All its rooms will also be deleted.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;">{{ $categories->links() }}</div>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">🗂</div>
        <h3>No categories yet</h3>
        <p style="margin-bottom:18px;">Get started by creating your first room category.</p>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-gold">+ New Category</a>
    </div>
    @endif
</div>

@endsection
