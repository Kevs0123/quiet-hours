@extends('layouts.app')
@section('title', 'New Client')
@section('content')

<div class="card" style="max-width:540px;">
    <div class="page-header" style="margin-bottom:24px;">
        <h1>New Client</h1>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline btn-sm">&larr; Back</a>
    </div>

    <form action="{{ route('admin.clients.store') }}" method="POST" novalidate>
        @csrf

        <div class="field">
            <label>Full Name <span style="color:var(--danger);">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}"
                   placeholder="e.g. Maria Santos"
                   @class(['is-invalid' => $errors->has('name')])>
            @error('name')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label>Email Address <span style="color:var(--danger);">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}"
                   placeholder="e.g. maria@example.com"
                   @class(['is-invalid' => $errors->has('email')])>
            @error('email')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label>Password <span style="color:var(--danger);">*</span>
                <span style="font-weight:400;text-transform:none;font-size:12px;color:var(--muted);">(min. 8 characters)</span>
            </label>
            <input type="password" name="password"
                   @class(['is-invalid' => $errors->has('password')])>
            @error('password')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label>Confirm Password <span style="color:var(--danger);">*</span></label>
            <input type="password" name="password_confirmation">
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <a href="{{ route('admin.clients.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-gold" style="flex:1;justify-content:center;">
                Create Client
            </button>
        </div>
    </form>
</div>

@endsection
