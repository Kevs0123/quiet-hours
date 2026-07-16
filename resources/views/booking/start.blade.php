@extends('layouts.app')
@section('title', 'Welcome, ' . $customerName)

@section('fullpage')
<style>
    .site-main { margin: 0; padding: 0; max-width: 100%; }

    /* ── Full-width stacked layout ───────────────────────────── */
    .client-page {
        min-height: auto;
        display: flex;
        flex-direction: column;
    }

    /* CONTENT panel — full width */
    .client-left {
        flex: 1;
        background: #fff;
        padding: 52px 48px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow-y: auto;
        max-width: 900px;
        margin: 0 auto;
        width: 100%;
    }

    /* HERO IMAGE — full width banner at top */
    .client-right {
        flex: 1;
        position: relative;
        overflow: hidden;
        height: 320px;
        order: -1;
    }

    .client-right img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    /* Gradient overlay on the image */
    .client-right::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to bottom,
            rgba(10,18,40,0.05) 0%,
            rgba(10,18,40,0.08) 50%,
            rgba(10,18,40,0.15) 100%
        );
    }

    /* Caption over image */
    .img-caption {
        position: absolute;
        bottom: 36px;
        left: 36px;
        z-index: 2;
        color: #fff;
        animation: fadeUp .6s ease .3s both;
    }
    .img-caption h2 {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        color: #fff;
        margin-bottom: 6px;
        text-shadow: 0 2px 12px rgba(0,0,0,.5);
    }
    .img-caption p {
        font-size: 14px;
        color: rgba(255,255,255,.80);
        margin: 0;
    }
    .img-rating {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(201,161,90,.90);
        color: #1b2a4a;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        margin-bottom: 10px;
    }

    /* ── Left panel styles ────────────────────────────────── */
    .client-badge {
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
        margin-bottom: 20px;
    }

    .client-left h1 {
        font-size: 34px;
        line-height: 1.15;
        color: #1b2a4a;
        margin-bottom: 10px;
    }
    .client-left h1 span { color: #c9a15a; }

    .client-left > p {
        font-size: 14.5px;
        color: #6b6860;
        line-height: 1.7;
        margin-bottom: 28px;
    }

    /* Step list */
    .step-list { margin-bottom: 32px; }

    .step-item {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 14px 0;
        border-bottom: 1px solid #f0ece4;
        animation: fadeUp .5s ease both;
    }
    .step-item:last-child { border-bottom: none; }
    .step-item:nth-child(2) { animation-delay: .07s; }
    .step-item:nth-child(3) { animation-delay: .14s; }
    .step-item:nth-child(4) { animation-delay: .21s; }

    .step-num {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #1b2a4a;
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1px;
    }
    .step-num.gold { background: #c9a15a; color: #1b2a4a; }

    .step-text h3 { font-size: 14px; color: #1b2a4a; margin-bottom: 3px; font-weight: 600; }
    .step-text p  { font-size: 12.5px; color: #6b6860; margin: 0; line-height: 1.5; }

    /* CTA button */
    .btn-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 15px 24px;
        background: #c9a15a;
        color: #1b2a4a;
        font-size: 15px;
        font-weight: 700;
        border-radius: 9px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: background .22s, transform .22s, box-shadow .22s;
        font-family: 'Inter', sans-serif;
        animation: fadeUp .5s ease .28s both;
    }
    .btn-cta:hover {
        background: #a6833e;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(201,161,90,.30);
    }

    /* Responsive — adjust padding on small screens */
    @media (max-width: 860px) {
        .client-left { padding: 36px 24px; }
        .client-right { height: 240px; }
    }
</style>

<div class="client-page">

    {{-- LEFT: content --}}
    <div class="client-left">

        <div class="client-badge">🏨 Quiet Hours Hotel</div>

        <h1>Welcome back,<br><span>{{ $customerName }}</span>!</h1>

        <p>Your comfort is our priority. Complete your booking in just a few steps below.</p>

        <div class="step-list">
            <div class="step-item">
                <div class="step-num gold">✓</div>
                <div class="step-text">
                    <h3>You're signed in</h3>
                    <p>Logged in as <strong>{{ auth()->user()->email ?? $customerName }}</strong>.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">1</div>
                <div class="step-text">
                    <h3>Booking Details</h3>
                    <p>Your reference ID is auto-generated. Tell us the room or event, dates, and number of guests.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">2</div>
                <div class="step-text">
                    <h3>Confirmation Upload</h3>
                    <p>Upload your booking confirmation — PDF, JPG, or PNG, up to 4MB.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">3</div>
                <div class="step-text">
                    <h3>Summary</h3>
                    <p>Review your complete booking details before finishing.</p>
                </div>
            </div>
        </div>

        <a href="{{ route('booking.details') }}" class="btn-cta">
            Continue to Booking &rarr;
        </a>

    </div>

    {{-- RIGHT: hotel image --}}
    <div class="client-right">
        {{-- Uses a high-quality hotel photo via Unsplash (no API key needed) --}}
        <img
            src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1400&q=85&auto=format&fit=crop"
            alt="Quiet Hours Hotel — luxury exterior"
            loading="eager"
        >

        <div class="img-caption">
            <div class="img-rating">★ 5.0 &nbsp; Luxury Hotel</div>
            <h2>Quiet Hours Hotel</h2>
            <p>Where every stay becomes a cherished memory.</p>
        </div>
    </div>

</div>
@endsection

@section('content')
@endsection
