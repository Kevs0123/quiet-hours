@extends('layouts.app')
@section('title', 'Edit Client — ' . $client->name)
@section('content')

<div class="card" style="max-width:540px;">
    <div class="page-header" style="margin-bottom:24px;">
        <h1>Edit Client</h1>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline btn-sm">&larr; Back</a>
    </div>

    <form action="{{ route('admin.clients.update', $client) }}" method="POST" novalidate>
        @csrf @method('PUT')

        <div class="field">
            <label>Full Name <span style="color:var(--danger);">*</span></label>
            <input type="text" name="name" value="{{ old('name', $client->name) }}"
                   @class(['is-invalid' => $errors->has('name')])>
            @error('name')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label>Email Address <span style="color:var(--danger);">*</span></label>
            <input type="email" name="email" value="{{ old('email', $client->email) }}"
                   @class(['is-invalid' => $errors->has('email')])>
            @error('email')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label>New Password
                <span style="font-weight:400;text-transform:none;font-size:12px;color:var(--muted);">
                    (leave blank to keep current)
                </span>
            </label>
            <input type="password" name="password"
                   @class(['is-invalid' => $errors->has('password')])>
            @error('password')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation">
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <a href="{{ route('admin.clients.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-gold" style="flex:1;justify-content:center;">
                Update Client
            </button>
        </div>
    </form>
</div>

@endsection
