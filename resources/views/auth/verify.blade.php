@extends('layouts.app')
@section('title', 'Verify Your Email')
@section('content')

<div class="card" style="max-width:520px;margin:36px auto;">
    <div class="page-header" style="margin-bottom:18px;">
        <h1>Enter Verification Code</h1>
    </div>

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('verify.post') }}" method="POST">
        @csrf

        <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', request('email')) }}" required>
        </div>

        <div class="field">
            <label>Verification Code</label>
            <input type="text" name="code" value="{{ old('code') }}" required maxlength="6">
            @error('code') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <a href="{{ route('login') }}" class="btn btn-outline">Back</a>
            <button type="submit" class="btn btn-gold" style="flex:1;justify-content:center;">Verify</button>
        </div>
    </form>

    <form action="{{ route('verify.resend') }}" method="POST" style="margin-top:12px;">
        @csrf
        <input type="hidden" name="email" value="{{ old('email', request('email')) }}">
        <button type="submit" class="btn" style="background:transparent;border:none;color:var(--muted);">Resend code</button>
    </form>
</div>

@endsection
