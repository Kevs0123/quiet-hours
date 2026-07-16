@extends('layouts.app')
@section('title', 'Sign In')
@section('content')

<div style="max-width:440px;margin:0 auto;">
    <div class="card">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="font-size:44px;margin-bottom:8px;">🔐</div>
            <h1 style="font-size:26px;">Sign In</h1>
            <p>Welcome back to Quiet Hours Hotel.</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" novalidate>
            @csrf

            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="you@example.com" autofocus
                       @class(['is-invalid' => $errors->has('email')])>
                @error('email')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••"
                       @class(['is-invalid' => $errors->has('password')])>
                @error('password')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="field" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="remember" id="remember" value="1"
                       style="width:16px;height:16px;flex-shrink:0;">
                <label for="remember" style="text-transform:none;font-size:13px;font-weight:500;letter-spacing:0;margin:0;cursor:pointer;">
                    Keep me signed in
                </label>
            </div>

            <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;margin-top:4px;">
                Sign In &rarr;
            </button>
        </form>

        <hr style="margin:22px 0;">

        <p style="text-align:center;font-size:13px;margin:0;">
            Don't have an account?
            <a href="{{ route('register') }}" style="color:var(--navy);font-weight:600;">Create one</a>
        </p>

        <div style="margin-top:20px;background:var(--cream);border-radius:8px;padding:14px 16px;font-size:12.5px;color:var(--muted);">
            <strong style="color:var(--navy);">Demo accounts:</strong><br>
            🔑 Admin &mdash; <code>admin@quiethours.com</code> / <code>admin1234</code><br>
            👤 Client &mdash; <code>client@quiethours.com</code> / <code>client1234</code>
        </div>
    </div>
</div>

@endsection
