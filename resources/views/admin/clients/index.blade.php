@extends('layouts.app')
@section('title', 'Clients')
@section('content')

<div class="card">
    <div class="page-header">
        <div>
            <h1>Clients</h1>
            <p style="margin:0;">Manage all registered client accounts.</p>
        </div>
        <a href="{{ route('admin.clients.create') }}" class="btn btn-gold">+ New Client</a>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.clients.index') }}"
          style="display:flex;gap:10px;margin-bottom:20px;">
        <input type="text" name="search" value="{{ $search }}"
               placeholder="Search by name or email…"
               style="max-width:320px;">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        @if($search)
            <a href="{{ route('admin.clients.index') }}" class="btn btn-outline btn-sm">Clear</a>
        @endif
    </form>

    @if($clients->isNotEmpty())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th style="text-align:center;">Bookings</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td style="color:var(--muted);font-size:13px;">{{ $clients->firstItem() + $loop->index }}</td>
                    <td>
                        <a href="{{ route('admin.clients.show', $client) }}" style="font-weight:600;">
                            {{ $client->name }}
                        </a>
                    </td>
                    <td style="font-size:13px;color:var(--muted);">{{ $client->email }}</td>
                    <td style="text-align:center;">
                        <span class="badge" style="background:var(--cream);color:var(--navy);">
                            {{ $client->bookings_count }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:var(--muted);">
                        {{ $client->created_at->format('M j, Y') }}
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('admin.clients.show', $client) }}"
                               class="btn btn-outline btn-sm">View</a>
                            <a href="{{ route('admin.clients.edit', $client) }}"
                               class="btn btn-outline btn-sm">Edit</a>
                            <form action="{{ route('admin.clients.destroy', $client) }}" method="POST"
                                  onsubmit="return confirm('Delete client {{ $client->name }}? Their bookings will remain.');">
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
    <div style="margin-top:20px;">{{ $clients->links() }}</div>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">👥</div>
        <h3>No clients found</h3>
        <p style="margin-bottom:18px;">
            {{ $search ? 'No results for "' . $search . '".' : 'No clients registered yet.' }}
        </p>
        <a href="{{ route('admin.clients.create') }}" class="btn btn-gold">+ New Client</a>
    </div>
    @endif
</div>

@endsection
