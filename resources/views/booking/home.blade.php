@extends('layouts.app')
@section('title', 'Book Your Stay')

@section('fullpage')
<style>
    .site-main { margin: 0; padding: 0; max-width: 100%; }

    .home-page {
        min-height: calc(100vh - 68px);
        display: flex;
        background: linear-gradient(135deg, #fcfaf6 0%, #f7efe2 100%);
    }

    .home-left {
        flex: 0 0 480px;
        background: #fff;
        padding: 52px 48px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .home-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px;
        background: linear-gradient(145deg, #f4e8d2 0%, #faf6ee 100%);
    }

    .home-side-card {
        width: 100%;
        max-width: 420px;
        background: rgba(255,255,255,0.92);
        border: 1px solid #eadfcf;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 18px 45px rgba(27,42,74,0.08);
    }

    .home-side-badge {
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
        padding: 6px 12px;
        border-radius: 999px;
        margin-bottom: 16px;
    }

    .home-side-card h2 {
        font-family: 'Playfair Display', serif;
        font-size: 26px;
        color: #1b2a4a;
        margin: 0 0 10px;
    }

    .home-side-card p {
        font-size: 14px;
        line-height: 1.7;
        color: #6b6860;
        margin-bottom: 18px;
    }

    .home-benefits {
        list-style: none;
        padding: 0;
        margin: 0 0 18px;
        display: grid;
        gap: 10px;
    }

    .home-benefits li {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        background: #fff;
        border: 1px solid #efe7da;
        border-radius: 10px;
        color: #31415a;
        font-size: 13px;
        font-weight: 600;
    }

    .home-benefits li::before {
        content: '✓';
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #e8f5ee;
        color: #2e7d32;
        font-size: 12px;
        flex-shrink: 0;
    }

    .home-highlight {
        padding: 14px 16px;
        background: #fdf6ec;
        border: 1px solid #ead7ab;
        border-radius: 12px;
        color: #5b4c25;
        font-size: 13px;
    }

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
        .home-page { flex-direction: column; }
        .home-left { flex: none; padding: 36px 24px; }
        .home-right { padding: 24px; }
    }
</style>

<div class="home-page">

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

    <div class="home-right">
        <div class="home-side-card">
            <div class="home-side-badge">Why guests choose us</div>
            <h2>Comfort, clarity, and care</h2>
            <p>Your booking journey stays simple from start to finish, with helpful guidance at every step.</p>
            <ul class="home-benefits">
                <li>Flexible room selection</li>
                <li>Easy date planning</li>
                <li>Fast confirmation upload</li>
                <li>Clear booking summary</li>
            </ul>
            <div class="home-highlight">
                <strong>Need help?</strong> Our team is ready to assist you with every reservation request.
            </div>
        </div>
    </div>

</div>
@endsection

@section('content')
@endsection
