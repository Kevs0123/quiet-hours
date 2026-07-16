@extends('layouts.app')
@section('title', 'Create Account')
@section('content')

<div style="max-width:440px;margin:0 auto;">
    <div class="card">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="font-size:44px;margin-bottom:8px;">✨</div>
            <h1 style="font-size:26px;">Create Account</h1>
            <p>Join Quiet Hours Hotel and start booking.</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" novalidate>
            @csrf

            <div class="field">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="e.g. Maria Santos" autofocus
                       @class(['is-invalid' => $errors->has('name')])>
                @error('name')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="you@example.com"
                       @class(['is-invalid' => $errors->has('email')])>
                @error('email')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label>Password <span style="font-weight:400;font-size:12px;color:var(--muted);">(min. 8 characters)</span></label>
                <input type="password" name="password" placeholder="••••••••"
                       @class(['is-invalid' => $errors->has('password')])>
                @error('password')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;margin-top:4px;">
                Create Account &rarr;
            </button>
        </form>

        <hr style="margin:22px 0;">

        <p style="text-align:center;font-size:13px;margin:0;">
            Already have an account?
            <a href="{{ route('login') }}" style="color:var(--navy);font-weight:600;">Sign in</a>
        </p>
    </div>
</div>

@endsection
