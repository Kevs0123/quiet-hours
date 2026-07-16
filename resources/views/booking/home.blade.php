@extends('layouts.app')
@section('title', 'Book Your Stay')

@section('fullpage')
<style>
    .site-main { margin: 0; padding: 0; max-width: 100%; }

    .home-page {
        min-height: calc(100vh - 68px);
        display: flex;
    }

    /* LEFT — form panel */
    .home-left {
        flex: 0 0 480px;
        background: #fff;
        padding: 52px 48px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* RIGHT — image */
    .home-right {
        flex: 1;
        position: relative;
        overflow: hidden;
    }
    .home-right img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 40%;
        display: block;
    }
    .home-right::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            135deg,
            rgba(10,18,40,0.50) 0%,
            rgba(10,18,40,0.15) 60%,
            rgba(10,18,40,0.40) 100%
        );
    }

    /* Overlay text on image */
    .home-img-overlay {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 40px;
        color: #fff;
    }
    .home-img-overlay h2 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        color: #fff;
        margin-bottom: 12px;
        text-shadow: 0 2px 16px rgba(0,0,0,.5);
        animation: fadeDown .6s ease both;
    }
    .home-img-overlay p {
        font-size: 15px;
        color: rgba(255,255,255,.82);
        max-width: 340px;
        line-height: 1.7;
        margin-bottom: 28px;
        animation: fadeDown .6s ease .1s both;
    }
    .home-img-tags {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
        animation: fadeUp .6s ease .2s both;
    }
    .home-img-tag {
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.30);
        backdrop-filter: blur(8px);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
    }

    /* LEFT panel styles */
    .home-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #fdf6ec;
        border: 1px solid #e8c97a;
        color: #a6833e;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 5px 13px;
        border-radius: 20px;
        margin-bottom: 22px;
        animation: fadeUp .4s ease both;
    }

    .home-left h1 {
        font-size: 32px;
        color: #1b2a4a;
        line-height: 1.2;
        margin-bottom: 10px;
        animation: fadeUp .45s ease .05s both;
    }
    .home-left > p {
        font-size: 14px;
        color: #6b6860;
        line-height: 1.7;
        margin-bottom: 30px;
        animation: fadeUp .45s ease .10s both;
    }

    .home-form { animation: fadeUp .45s ease .15s both; }

    .home-form .field { margin-bottom: 18px; }
    .home-form label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #1b2a4a;
        margin-bottom: 7px;
    }
    .home-form input[type=text] {
        width: 100%;
        padding: 12px 15px;
        border: 1.5px solid #d6d0c2;
        border-radius: 8px;
        font-size: 15px;
        font-family: 'Inter', sans-serif;
        color: #22242b;
        transition: border-color .2s, box-shadow .2s;
    }
    .home-form input[type=text]:focus {
        outline: none;
        border-color: #c9a15a;
        box-shadow: 0 0 0 3px rgba(201,161,90,.18);
    }
    .home-form input.is-invalid { border-color: #b3261e; }
    .error-text { color: #b3261e; font-size: 12.5px; margin-top: 5px; display: block; }

    .btn-home-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px 24px;
        background: #c9a15a;
        color: #1b2a4a;
        font-size: 15px;
        font-weight: 700;
        border-radius: 9px;
        border: none;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: background .22s, transform .22s, box-shadow .22s;
        margin-top: 4px;
    }
    .btn-home-submit:hover {
        background: #a6833e;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(201,161,90,.28);
    }

    .home-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 24px 0;
        font-size: 12px;
        color: #b0a898;
    }
    .home-divider::before, .home-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e6e2d8;
    }

    .home-login-link {
        text-align: center;
        font-size: 13px;
        color: #6b6860;
    }
    .home-login-link a { color: #1b2a4a; font-weight: 600; text-decoration: none; }
    .home-login-link a:hover { color: #c9a15a; }

    @media (max-width: 860px) {
        .home-page { flex-direction: column-reverse; }
        .home-left { flex: none; padding: 36px 24px; }
        .home-right { min-height: 300px; flex: none; }
    }
</style>

<div class="home-page">

    {{-- LEFT: booking start form --}}
    <div class="home-left">

        <div class="home-badge">🏨 Quiet Hours Hotel</div>

        <h1>Book Your Perfect Stay</h1>
        <p>Enter your name below to begin the booking process. It only takes a few minutes.</p>

        <div class="home-form">
            <form action="{{ route('booking.go') }}" method="POST" novalidate>
                @csrf
                <div class="field">
                    <label>Your Full Name</label>
                    <input type="text"
                           name="customer_name"
                           value="{{ old('customer_name') }}"
                           placeholder="e.g. Maria Santos"
                           autofocus
                           @class(['is-invalid' => $errors->has('customer_name')])>
                    @error('customer_name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn-home-submit">
                    Start Booking &rarr;
                </button>
            </form>

            <div class="home-divider">or</div>

            <div class="home-login-link">
                Already have an account?
                <a href="{{ route('login') }}">Sign in</a>
                for a faster experience.
            </div>
        </div>

    </div>

    {{-- RIGHT: hotel image with overlay text --}}
    <div class="home-right">
        <img
            src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1400&q=85&auto=format&fit=crop"
            alt="Quiet Hours Hotel lobby"
            loading="eager"
        >
        <div class="home-img-overlay">
            <h2>Experience Quiet Luxury</h2>
            <p>From elegant rooms to world-class service — your perfect getaway starts here.</p>
            <div class="home-img-tags">
                <span class="home-img-tag">🛏 Premium Rooms</span>
                <span class="home-img-tag">🍽 Fine Dining</span>
                <span class="home-img-tag">🌊 Spa & Wellness</span>
                <span class="home-img-tag">⭐ 5-Star Service</span>
            </div>
        </div>
    </div>

</div>
@endsection

@section('content')
@endsection
